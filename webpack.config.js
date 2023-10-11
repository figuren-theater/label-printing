const defaultConfig = require('@wordpress/scripts/config/webpack.config');
module.exports = {
	...defaultConfig,
	entry: {
		'label-printing/label-printing':
			'./src/block-editor/blocks/label-printing',
		'label-proxy/label-proxy': './src/block-editor/blocks/label-proxy',

		'label-overview/label-overview': './src/block-editor/variations/label-overview',
	},
};
