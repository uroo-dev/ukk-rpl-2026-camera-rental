/**
 * Donezo UI — Vendor Asset Copier
 * Copies third-party JS (Chart.js, Lucide) from node_modules to assets/vendor/
 */
const fs = require('fs');
const path = require('path');

const vendorDir = path.join(__dirname, '..', 'assets', 'vendor');

// Ensure vendor directory exists
if (!fs.existsSync(vendorDir)) {
  fs.mkdirSync(vendorDir, { recursive: true });
}

const assets = [
  {
    src: path.join(__dirname, '..', 'node_modules', 'chart.js', 'dist', 'chart.umd.min.js'),
    dest: path.join(vendorDir, 'chart.min.js'),
    label: 'Chart.js',
  },
  {
    src: path.join(__dirname, '..', 'node_modules', 'lucide', 'dist', 'umd', 'lucide.min.js'),
    dest: path.join(vendorDir, 'lucide.min.js'),
    label: 'Lucide Icons',
  },
];

assets.forEach((asset) => {
  try {
    if (fs.existsSync(asset.src)) {
      fs.copyFileSync(asset.src, asset.dest);
      const size = fs.statSync(asset.dest).size;
      console.log(`✓ ${asset.label} copied (${(size / 1024).toFixed(1)} KB)`);
    } else {
      console.warn(`⚠ ${asset.label} source not found: ${asset.src}`);
    }
  } catch (err) {
    console.error(`✗ Failed to copy ${asset.label}:`, err.message);
  }
});

console.log('Vendor copy complete.');
