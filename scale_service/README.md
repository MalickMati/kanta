# Scale Service (Node.js)

This microservice connects to the physical scale via USB/Serial and broadcasts the weight locally over WebSockets. It is designed to be fully offline, robust, and will automatically reconnect if the scale is unplugged.

## PM2 Installation and Running

To ensure this service runs fully automatically in the background, starts on boot, and restarts if it crashes, use PM2.

### 1. Install PM2 (if not already installed)
If you haven't installed PM2 globally, do so using:
```bash
sudo npm install -g pm2
```

### 2. Start the Service
Run the following command from this directory (`scale_service`):
```bash
pm2 start index.js --name scale-service
```

### 3. Save PM2 State (Starts on Boot)
To make sure PM2 restarts the service automatically when the computer reboots:
```bash
pm2 save
pm2 startup
```
(Follow the instructions printed on the screen after running `pm2 startup` if it asks you to run an additional command).

### 4. Viewing Logs (Error Monitoring)
To monitor errors, connections, or hardware disconnects, you can view the live logs:
```bash
pm2 logs scale-service
```

## Frontend Integration (Offline Mode)
The frontend Blade templates can connect to this service without internet access using the Socket.io client script served directly by the Node server.

Add this to your Blade file before your custom scripts:
```html
<script src="http://localhost:3001/socket.io/socket.io.js"></script>
```

Then in your Javascript:
```javascript
const socket = io('http://localhost:3001');

socket.on('weight_reading', (weight) => {
    console.log("Live Weight:", weight);
    // document.getElementById('weight-input').value = weight;
});

socket.on('scale_error', (msg) => {
    console.error("Scale Error:", msg);
    // Display error to user
});

socket.on('scale_status', (msg) => {
    console.log("Scale Status:", msg);
});
```
