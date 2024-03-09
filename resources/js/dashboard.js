import grapesjs from 'grapesjs';
import 'grapesjs/dist/css/grapes.min.css';
import gjsPresetWebpage from 'grapesjs-preset-webpage';

document.addEventListener('DOMContentLoaded', function () {
  const editor = grapesjs.init({
    container: '#gjs',
    fromElement: true,
    height: '925px',
    width: 'auto',
    storageManager: { autoload: 0 },
    plugins: [gjsPresetWebpage],
    pluginsOpts: {
      gjsPresetWebpage: {}
    },
  });
});
