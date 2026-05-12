const express = require('express');
const cors = require('cors');
const puppeteer = require('puppeteer');
const { exec } = require('child_process');
const fs = require('fs');
const path = require('path');

const app = express();
const port = process.env.PORT || 3000;

// Logging utility
const logFilePath = path.join(__dirname, 'printer.log');
const log = (message, type = 'INFO') => {
    const timestamp = new Date().toISOString();
    const logMessage = `[${timestamp}] [${type}] ${message}\n`;
    console.log(logMessage.trim());
    fs.appendFileSync(logFilePath, logMessage);
};

app.use(cors());
app.use(express.json({ limit: '50mb' }));

app.post('/print', async (req, res) => {
    const { html, quantity } = req.body;

    if (!html) {
        log('HTML content is missing in request', 'ERROR');
        return res.status(400).json({ error: 'HTML content is required.' });
    }

    const copies = parseInt(quantity) || 1;
    const tempPdfPath = path.join(__dirname, `temp_print_${Date.now()}.pdf`);

    let browser;
    try {
        log(`Received print request for ${copies} copies.`);
        
        // Launch puppeteer - Use system chrome if available to avoid download issues
        const executablePath = '/usr/bin/google-chrome';
        const launchOptions = {
            headless: 'new',
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        };

        if (fs.existsSync(executablePath)) {
            launchOptions.executablePath = executablePath;
            log(`Using system chrome at ${executablePath}`);
        } else {
            log('System chrome not found at /usr/bin/google-chrome, attempting default launch', 'WARNING');
        }

        browser = await puppeteer.launch(launchOptions);
        
        const page = await browser.newPage();
        
        // Set content and wait for network idle to ensure everything is loaded
        await page.setContent(html, { waitUntil: 'networkidle0' });

        // Emulate print media type
        await page.emulateMediaType('print');

        // Generate PDF
        await page.pdf({
            path: tempPdfPath,
            format: 'A4',
            printBackground: true,
            margin: { top: '0', right: '0', bottom: '0', left: '0' }
        });

        await browser.close();
        log(`Generated PDF at ${tempPdfPath}`);

        // Print using lp command (Linux)
        const command = `lp -n ${copies} "${tempPdfPath}"`;
        exec(command, (error, stdout, stderr) => {
            // Clean up temp file
            if (fs.existsSync(tempPdfPath)) {
                fs.unlinkSync(tempPdfPath);
            }

            if (error) {
                log(`Error executing print command: ${error.message}`, 'ERROR');
                return res.status(500).json({ error: 'Failed to print', details: error.message });
            }
            if (stderr) {
                log(`Print command stderr: ${stderr}`, 'WARNING');
            }

            log(`Print successful: ${stdout.trim()}`);
            return res.status(200).json({ success: true, message: 'Print job sent successfully.' });
        });

    } catch (error) {
        log(`Exception in /print: ${error.stack}`, 'ERROR');
        
        if (browser) {
            await browser.close();
        }
        
        if (fs.existsSync(tempPdfPath)) {
            fs.unlinkSync(tempPdfPath);
        }

        res.status(500).json({ error: 'Internal server error', details: error.message });
    }
});

app.listen(port, () => {
    log(`Printer Microservice is running on http://localhost:${port}`);
    log('Ensure you have a default system printer configured (e.g. via CUPS on Linux).');
});
