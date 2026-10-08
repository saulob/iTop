/**
 * 
 * @api
 * @since 3.2.0
 */
const CombodoCKEditorHandler = {
	instances: {},
	instances_promise: {},
	is_upload_configured: {},

	/**
	 *
	 * @param sElem
	 * @param aConfiguration
	 * @constructor
	 */
	PrepareConfiguration: function(sElem, aConfiguration){

		// mention
		if(aConfiguration !== undefined && aConfiguration['mention'] !== undefined){

			// iterate throw feeds...
			aConfiguration['mention']['feeds'].forEach(function(e){

				// ajax feed
				if(e['feed_type'] === 'ajax'){

					// feed callback
					e['feed'] = CombodoCKEditorFeeds.getAjaxItems(e['feed_ajax_options']);

					// feed item render
					e['itemRenderer'] = CombodoCKEditorFeeds.customItemRenderer(sElem);
				}
			})
		}
	},

	/**
	 * Make the oElem enter the fullscreen mode, meaning that it will take all the screen and be above everything else.
	 *
	 * @param {string} sElem The id object of the element
	 * @param {array} aConfiguration The CKEditor configuration
	 * @constructor
	 */
	CreateInstance: function (sElem, aConfiguration) {

		// prepare configuration
		CombodoCKEditorHandler.PrepareConfiguration(sElem, aConfiguration);

		this.is_upload_configured[sElem] = false;
		// create instance
		return this.instances_promise[sElem] = new Promise((resolve, reject) => {
            ClassicEditor.create(Object.assign({
                attachTo: $(sElem)[0],
                licenseKey: 'GPL',
            }, aConfiguration))
			.then(editor => {
				// Adjust size if passed in configuration
				// - Width
				if (aConfiguration.width !== undefined) {
                    editor.editing.view.change( writer => { writer.setStyle( 'width', aConfiguration.width, editor.editing.view.document.getRoot() ); } );
				}
				// - Height
				if (aConfiguration.height !== undefined) {
                    editor.editing.view.change( writer => { writer.setStyle( 'height', aConfiguration.height, editor.editing.view.document.getRoot() ); } );
				}

				this.instances[sElem] = editor;
				resolve(editor);
			})
			.catch( error => {
				console.error( error );
			} );
		});
	},
	DeleteInstance: async function(sElem){
		let oInstance = await this.GetInstance(sElem);
		if (oInstance) {
			oInstance.destroy().then(() => {
				CombodoJSConsole.Debug('CKEditor for #'+sId+' destroyed successfully');
			}).catch(error => {
				CombodoJSConsole.Error('Error during #'+sId+' editor destruction:' + error);
			});
		}
	},
	GetInstance: async function(sElem){
		if (this.instances[sElem]) {
			return this.instances[sElem];
		}
		else{
			let oEditor = null
			if(!this.instances_promise[sElem]){
				this.instances_promise[sElem] = new Promise((resolve, reject) => {
				});
			}
			await this.instances_promise[sElem].then((editor) => {
				oEditor = editor;
			});
			return oEditor;
		}
	},
	GetInstanceSynchronous: function(sElem) {
		return this.instances[sElem];
	},
	EnableImageUpload: async function (sElem, sUrl) {
		if (this.is_upload_configured[sElem]) {
			return;
		}
		const editor = await this.GetInstance(sElem);

		class SimpleUploadAdapter {
			constructor(loader) {
				this.loader = loader;
			}

			upload() {
				return this.loader.file
					.then(file => new Promise((resolve, reject) => {
						// Replace 'your-upload-url' with your server-side upload endpoint
						const uploadUrl = sUrl;

						const formData = new FormData();
						formData.append('upload', file);

						CombodoHTTP.Fetch(uploadUrl, {
								method: 'POST',
								body: formData
							})
							.then(response => response.json())
							.then(responseData => {
								if (responseData.uploaded) {
									resolve({default: responseData.url});
								} else {
									reject(responseData.error.message || 'Upload failed');
								}
							})
							.catch(error => {
								reject('Upload failed due to a network error.');
							});
					}));
			}
		}

		// Enable the custom upload adapter
		editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
			return new SimpleUploadAdapter(loader);
		};

		// Upload images pasted from a web page instead of keeping their external URL
		// The clipboard then contains both the HTML (<img src="external URL">) and the image file, but the default
		// ImageUploadEditing listener ignores files as soon as there is HTML, so we handle this case before it.
		// Default behavior is kept when the HTML has real content besides images (eg. Excel cells come with a PNG of the table)
		editor.editing.view.document.on('clipboardInput', (evt, data) => {
			const oDataTransfer = data.dataTransfer;
			const sHtml = oDataTransfer.getData('text/html') || '';
			// Without HTML (eg. screenshot), files are already uploaded by the default listener
			if (sHtml === '') {
				return;
			}

			const aImageTypes = editor.config.get('image.upload.types');
			const oUploadImageCommand = editor.commands.get('uploadImage');
			if (!Array.isArray(aImageTypes) || !oUploadImageCommand || !oUploadImageCommand.isEnabled) {
				return;
			}

			const oImageTypesRegExp = new RegExp('^image\\/(' + aImageTypes.map(sType => sType.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|') + ')$');
			const aFiles = Array.from(oDataTransfer.files).filter(oFile => oFile && oImageTypesRegExp.test(oFile.type));
			if (aFiles.length === 0) {
				return;
			}

			// Keep default behavior if the HTML has more images than files or any real content besides images
			const oPastedDoc = new DOMParser().parseFromString(sHtml, 'text/html');
			if (oPastedDoc.body.querySelectorAll('img').length > aFiles.length) {
				return;
			}
			oPastedDoc.body.querySelectorAll('img, picture, source').forEach(oElem => oElem.remove());
			if (oPastedDoc.body.textContent.trim() !== '' || oPastedDoc.body.querySelector('table, video, audio, iframe, embed, object, svg, canvas') !== null) {
				return;
			}

			// Pasted content is only images, upload them through the upload adapter
			evt.stop();
			editor.model.change(writer => {
				if (data.targetRanges) {
					writer.setSelection(data.targetRanges.map(oViewRange => editor.editing.mapper.toModelRange(oViewRange)));
				}
				editor.execute('uploadImage', {file: aFiles});
			});
		}, {priority: 'high'});

		this.is_upload_configured[sElem] = true;
	},
	InsertHtmlInsideInstance: function(sElem, sHtml){

		CombodoCKEditorHandler.GetInstance(sElem).then((oCKEditor) => {
			oCKEditor.execute('insert-html', sHtml);
		});

	}
}
