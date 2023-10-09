//
import ServerSideRender from '@wordpress/server-side-render';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @todo
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	return <ServerSideRender block="figuren-theater/label-proxy" />;
}
