const express = require('express');
const { SerialPort, ReadlineParser } = require('serialport');
const { Server } = require('socket.io');
const http = require('http');

// Configuration
const WS_PORT = process.env.WS_PORT || 3001; // WebSocket port
const BAUD_RATE = process.env.BAUD_RATE || 9600;
const RECONNECT_DELAY = 5000; // ms to wait before reconnecting

// Setup Express and HTTP Server for Socket.io
const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*", // Allow any origin for local usage
        methods: ["GET", "POST"]
    }
});

// Serve a basic status page if someone visits the port
app.get('/', (req, res) => {
    res.send('Scale Service is running. Connect via WebSocket.');
});

let currentPort = null;
let parser = null;

// Helper to log with timestamp
function log(msg) {
    console.log(`[${new Date().toISOString()}] ${msg}`);
}

// Function to auto-detect the serial port based on the PHP logic (/dev/ttyUSB*, /dev/ttyS*, etc)
async function detectSerialPort() {
    try {
        const ports = await SerialPort.list();
        if (ports.length === 0) return null;

        // Try to prioritize USB devices over standard serial if multiple exist
        const usbPorts = ports.filter(p => p.path.includes('USB') || p.path.includes('ACM'));
        if (usbPorts.length > 0) return usbPorts[0].path;

        // Fallback to first available port
        return ports[0].path;
    } catch (err) {
        log(`Error listing serial ports: ${err.message}`);
        return null;
    }
}

// Connect and listen to the serial port
async function connectToScale() {
    const devicePath = process.env.DEVICE_PATH || await detectSerialPort();

    if (!devicePath) {
        log('No serial device found. Retrying in 5 seconds...');
        io.emit('scale_error', 'No serial device found. Ensure scale is connected.');
        setTimeout(connectToScale, RECONNECT_DELAY);
        return;
    }

    log(`Attempting to connect to ${devicePath} at ${BAUD_RATE} baud...`);

    currentPort = new SerialPort({
        path: devicePath,
        baudRate: parseInt(BAUD_RATE),
        autoOpen: false,
    });

    parser = currentPort.pipe(new ReadlineParser({ delimiter: '\n' }));

    currentPort.open((err) => {
        if (err) {
            log(`Error opening port: ${err.message}`);
            io.emit('scale_error', `Error opening port ${devicePath}: ${err.message}`);
            setTimeout(connectToScale, RECONNECT_DELAY);
            return;
        }
        log(`Successfully opened port ${devicePath}`);
        io.emit('scale_status', `Connected to scale on ${devicePath}`);
    });

    // Handle incoming data
    parser.on('data', (data) => {
        const line = data.trim();
        // Remove common unit suffixes like kg, g, lb, etc.
        const clean = line.replace(/\s*(kg|g|lb|lbs|oz)\b/ig, '').trim();
        
        // Extract numeric weight
        const match = clean.match(/([+-]?\d+(?:[.,]\d+)?)/);
        if (match) {
            let raw = match[1].replace(',', '.');
            if (!isNaN(raw)) {
                let weight = parseFloat(raw);
                // Broadcast to all connected clients
                io.emit('weight_reading', weight);
                // Uncomment to debug every reading
                // log(`Reading: ${weight}`);
            }
        }
    });

    // Handle Disconnection / Errors
    currentPort.on('close', () => {
        log('Serial port closed. Scale may have been disconnected.');
        io.emit('scale_error', 'Scale disconnected. Attempting to reconnect...');
        io.emit('scale_status', 'Disconnected');
        
        // Cleanup and attempt reconnect
        currentPort.removeAllListeners();
        currentPort = null;
        setTimeout(connectToScale, RECONNECT_DELAY);
    });

    currentPort.on('error', (err) => {
        log(`Serial port error: ${err.message}`);
        io.emit('scale_error', `Hardware Error: ${err.message}`);
    });
}

// Socket.io Connection Logic
io.on('connection', (socket) => {
    log(`Frontend client connected: ${socket.id}`);
    
    // Immediately send current status
    if (currentPort && currentPort.isOpen) {
        socket.emit('scale_status', `Connected to scale on ${currentPort.path}`);
    } else {
        socket.emit('scale_status', 'Disconnected');
    }

    socket.on('disconnect', () => {
        log(`Frontend client disconnected: ${socket.id}`);
    });
});

// Start Server and Scale Connection
server.listen(WS_PORT, () => {
    log(`Scale Microservice WebSocket server running on port ${WS_PORT}`);
    connectToScale();
});
