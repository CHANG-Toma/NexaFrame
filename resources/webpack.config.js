const path = require('path');

module.exports = {
  // Mode de développement
  mode: 'development', 
  // Point d'entrée de l'application
  entry: './js/dashboard.js', 
  // Sortie du bundle
  output: {
    path: path.resolve('../public/assets/dist/js/'),
    filename: 'bundle.js',
  },
  // Modules et règles pour le traitement des fichiers
  module: {
    rules: [
      {
        test: /\.js$/,  // tous les fichiers .js
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader', // utilisation de babel-loader pour les transpiler
        },
      },
      {
        test: /\.css$/, // tous les fichiers .css
        use: ['style-loader', 'css-loader'], // utilisation de style-loader et css-loader pour les traiter
      },
    ],
  },
  // Configuration pour le serveur de développement
  devServer: {
    static: {
      directory: path.join(__dirname, 'public'), // Dossier statique pour le serveur de développement
    },
    compress: true,  // Active la compression gzip
    port: 9000,  // Port pour le serveur de développement
  },
  // Source maps pour le débogage
  devtool: 'inline-source-map',
};
