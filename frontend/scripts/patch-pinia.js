import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const piniaDistDir = path.resolve(__dirname, '../node_modules/pinia/dist');

if (!fs.existsSync(piniaDistDir)) {
  console.log('[patch-pinia] pinia/dist not found, skipping patch.');
  process.exit(0);
}

const files = fs.readdirSync(piniaDistDir);

let patchedCount = 0;

for (const file of files) {
  if (!file.endsWith('.js') && !file.endsWith('.mjs') && !file.endsWith('.cjs')) {
    continue;
  }

  const filePath = path.join(piniaDistDir, file);
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;

  // Fix 1: obj.hasOwnProperty(skipHydrateSymbol) -> Object.prototype.hasOwnProperty.call(obj, skipHydrateSymbol)
  if (content.includes('!obj.hasOwnProperty(skipHydrateSymbol)')) {
    content = content.replaceAll(
      '!obj.hasOwnProperty(skipHydrateSymbol)',
      '!Object.prototype.hasOwnProperty.call(obj, skipHydrateSymbol)'
    );
    modified = true;
  } else if (content.includes('!obj.hasOwnProperty(y)')) {
    content = content.replaceAll(
      '!obj.hasOwnProperty(y)',
      '!Object.prototype.hasOwnProperty.call(obj, y)'
    );
    modified = true;
  }

  // Fix 2: !patchToApply.hasOwnProperty(key) -> !Object.prototype.hasOwnProperty.call(patchToApply, key)
  if (content.includes('!patchToApply.hasOwnProperty(key)')) {
    content = content.replaceAll(
      '!patchToApply.hasOwnProperty(key)',
      '!Object.prototype.hasOwnProperty.call(patchToApply, key)'
    );
    modified = true;
  }

  // Fix 3: target.hasOwnProperty(key) -> Object.prototype.hasOwnProperty.call(target, key)
  if (content.includes('target.hasOwnProperty(key)')) {
    content = content.replaceAll(
      'target.hasOwnProperty(key)',
      'Object.prototype.hasOwnProperty.call(target, key)'
    );
    modified = true;
  }

  // Fix 4: minified prod bundle: !n.hasOwnProperty(i) and t.hasOwnProperty(i)
  if (content.includes('!n.hasOwnProperty(i)')) {
    content = content.replaceAll(
      '!n.hasOwnProperty(i)',
      '!Object.prototype.hasOwnProperty.call(n, i)'
    );
    modified = true;
  }
  if (content.includes('t.hasOwnProperty(i)')) {
    content = content.replaceAll(
      't.hasOwnProperty(i)',
      'Object.prototype.hasOwnProperty.call(t, i)'
    );
    modified = true;
  }
  if (content.includes('!t.hasOwnProperty(y)')) {
    content = content.replaceAll(
      '!t.hasOwnProperty(y)',
      '!Object.prototype.hasOwnProperty.call(t, y)'
    );
    modified = true;
  }

  if (modified) {
    fs.writeFileSync(filePath, content, 'utf8');
    patchedCount++;
    console.log(`[patch-pinia] Successfully patched: ${file}`);
  }
}

console.log(`[patch-pinia] Finished. Patched ${patchedCount} files in pinia/dist.`);
