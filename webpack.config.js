const defaultConfig = require('@wordpress/scripts/config/webpack.config');
module.exports = {
	...defaultConfig,
	entry: {
		'premiere/premiere': './src/block-editor/blocks/premiere',
		'duration/duration': './src/block-editor/blocks/duration',
		'targetgroup/targetgroup': './src/block-editor/blocks/targetgroup',

		'document-setting-panel':
			'./src/block-editor/plugins/document-setting-panel',
	},
};
