const { join } = require('path');

module.exports = {
	defaultValues: {
		wpScripts: false,
		editorScript: 'file:../../../../build/{{slug}}.js',
		editorStyle: 'file:../../../../build/{{slug}}.css',
		style: 'file:../../../../build/style-{{slug}}.css',
	},
	templatesPath: join(__dirname, 'templates'),
};
