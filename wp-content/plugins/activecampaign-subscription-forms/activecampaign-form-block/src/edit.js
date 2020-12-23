/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { SelectControl } from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props]           Properties passed from the editor.
 * @param {string} [props.className] Class name generated for the block.
 *
 * @return {{label: string, value: string}} Element to render.
 */
export default function Edit( { attributes, className, setAttributes } ) {

	let forms = attributes.settings_activecampaign.forms;
	if( !forms ) {
		return <p>Please return to settings and select forms to store locally</p>;
	}

	let options = [];
	forms = Object.values(forms);
	for (let i = 0; i < forms.length; i++) {
		let form = forms[i];
		options.push({ label: form.name, value: form.id });
	}


	if(!attributes.formId) {
		setAttributes({formId: forms[0].id});
	}

	const selectControl = <SelectControl
		className = {'activecampaign-select-control'}
		label="Select a form:"
		value={ attributes.formId }
		options={ options }
		onChange={ ( val ) => setAttributes( { formId: val } ) }
	/>

	return (
		<div className={'wp-activecampaign-form-block'} >
			<p style={{align:'left'}}>Select an ActiveCampaign Form</p>
			<div >{selectControl}</div>
		</div>
	);
}




