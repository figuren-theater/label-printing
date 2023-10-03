const defaultConfig = require('@wordpress/scripts/config/webpack.config');
module.exports = {
	...defaultConfig,
	entry: {
		'label-overview': './src/block-editor/variations/label-overview',
	},
};
