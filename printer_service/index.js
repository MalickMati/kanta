const express = require('express');
const cors = require('cors');
const puppeteer = require('puppeteer');
const { exec } = require('child_process');
const fs = require('fs');
const path = require('path');

const app = express();
const port = process.env.PORT || 3000;

app.use(cors());
app.use(express.json({ limit: '50mb' }));

app.post('/print', async (req, res) => {
    const { html, quantity } = req.body;

    if (!html) {
        return res.status(400).json({ error: 'HTML content is required.' });
    }

    const copies = parseInt(quantity) || 1;
    const tempPdfPath = path.join(__dirname, `temp_print_${Date.now()}.pdf`);

    let browser;
    try {
        console.log(`Received print request for ${copies} copies.`);
        
        // Launch puppeteer
        browser = await puppeteer.launch({
            headless: 'new',
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
        
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
        console.log(`Generated PDF at ${tempPdfPath}`);

        // Print using lp command (Linux)
        const command = `lp -n ${copies} "${tempPdfPath}"`;
        exec(command, (error, stdout, stderr) => {
            // Clean up temp file
            if (fs.existsSync(tempPdfPath)) {
                fs.unlinkSync(tempPdfPath);
            }

            if (error) {
                console.error(`Error executing print command: ${error.message}`);
                return res.status(500).json({ error: 'Failed to print', details: error.message });
            }
            if (stderr) {
                console.error(`Print command stderr: ${stderr}`);
            }

            console.log(`Print successful: ${stdout}`);
            return res.status(200).json({ success: true, message: 'Print job sent successfully.' });
        });

    } catch (error) {
        console.error('Error in /print:', error);
        
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
    console.log(`Printer Microservice is running on http://localhost:${port}`);
    console.log('Ensure you have a default system printer configured (e.g. via CUPS on Linux).');
});
