/**
 * Handles the Preview Snippet on the Edit screen.
 *
 * @since 3.3.0
 * 
 * @package all-in-one-seo-pack
 * @package xregexp
 */

jQuery(function($){

	"use strict";

	let docTitle                 = '';
	let snippetTitle             = $('#aiosp_snippet_title');
	let snippetDescription       = $('#aioseop_snippet_description');
	let aioseopTitle             = $('input[name="aiosp_title"]');
	let aioseopDescription       = $('textarea[name="aiosp_description"]');
	let timeout                  = 0;
	let isGutenberg              = aioseop_preview_snippet.isGutenberg;
	let autogenerateDescriptions = aioseop_preview_snippet.autogenerateDescriptions;
	let skipExcerpt              = aioseop_preview_snippet.skipExcerpt;

	aioseopUpdateMetabox();

	/**
	 * The aioseopUpdateMetabox() function.
	 * 
	 * Updates the preview snippet and input field placeholders in the meta box when a change happens.
	 * 
	 * @since 3.3.0
	 */
	function aioseopUpdateMetabox() {
		let inputFields = [aioseopTitle, aioseopDescription];

		if ('false' === isGutenberg) {
			docTitle = $('#title');
			let postExcerpt = $('#excerpt');

			inputFields.push(docTitle, postExcerpt);

			setTimeout(function () {
				tinymce.editors[0].on('KeyUp', function () {
					aioseopUpdatePreviewSnippet();
				});
			}, 1000);
		}
		else {
			window._wpLoadBlockEditor.then(function () {
				setTimeout(function () {
					// https://developer.wordpress.org/block-editor/packages/packages-data/
					wp.data.subscribe(function () {
						clearTimeout(timeout);
						// This is needed because the code otherwise is triggered dozens of times.
						timeout = setTimeout(function () {
							aioseopUpdatePreviewSnippet();
						}, 200);
					});
				});
			});
		}

		inputFields.forEach(addEvent);
		function addEvent(item) {
			item.on('input', function () {
				aioseopUpdatePreviewSnippet();
			});
		}

		//Run once on page load.
		timeout = setTimeout(function () {
			aioseopUpdatePreviewSnippet();
		}, 1000);
	}

	/**
	 * AIOSEOP Update Preview Snippet
	 *
	 * @uses wp.data.select().getEditedPostAttribute()
	 * @link https://developer.wordpress.org/block-editor/data/data-core-editor/#getEditedPostAttribute
	 *
	 * @since 3.3
	 */
	function aioseopUpdatePreviewSnippet() {
		let postTitle   = '';
		let postContent = '';
		let postExcerpt = '';

		if ('false' === isGutenberg) {
			postTitle   = aioseopStripMarkup($.trim($('#title').val()));
			postContent = aioseopShortenDescription($('#content_ifr').contents().find('body')[0].innerHTML);
			postExcerpt = aioseopShortenDescription($.trim($('#excerpt').val()));
		}
		else {
			postTitle   = aioseopStripMarkup($.trim($('#post-title-0').val()));
			postContent = aioseopShortenDescription(wp.data.select('core/editor').getEditedPostAttribute('content'));
			postExcerpt = aioseopShortenDescription(wp.data.select('core/editor').getEditedPostAttribute('excerpt'));
		}

		let metaboxTitle       = aioseopStripMarkup($.trim($('input[name="aiosp_title').val()));
		let metaboxDescription = aioseopStripMarkup($.trim($('textarea[name="aiosp_description"]').val()));

		snippetTitle.text(postTitle);
		aioseopTitle.attr('placeholder', postTitle);

		if ('' !== metaboxTitle) {
			snippetTitle.text(metaboxTitle);
		}

		if ('on' === autogenerateDescriptions) {
			snippetDescription.text(postContent);
			aioseopDescription.attr('placeholder', postContent);

			if ('on' !== skipExcerpt & '' !== postExcerpt) {
				snippetDescription.text(postExcerpt);
				aioseopDescription.attr('placeholder', postExcerpt);
			}
		} else {
			snippetDescription.text("");
			aioseopDescription.attr('placeholder', "");
		}

		if ('' !== metaboxDescription) {
			snippetDescription.text(metaboxDescription);
			aioseopDescription.attr('placeholder', metaboxDescription);
		}
	}

	/**
	 * The aioseopShortenDescription() function.
	 * 
	 * Shortens the description to max. 160 characters without truncation.
	 * 
	 * @since 3.3.0
	 * 
	 * @param string description 
	 */
	function aioseopShortenDescription(description) {
		description = aioseopStripMarkup(description);
		if (160 < description.length) {
			let excessLength = description.length - 160;
			let regex = new XRegExp("[^\\pZ\\pP]*.{" + excessLength + "}$");
			description = XRegExp.replace(description, regex, '');
			description = description + " ...";
		}
		return description;
	}

	/**
	 * The aioseopStripMarkup() function.
	 * 
	 * Strips all editor markup from a string.
	 * 
	 * @since 3.3.0
	 * 
	 * @param string content
	 * @return string 
	  */
	function aioseopStripMarkup(content) {
		// Remove all HTML tags.
		content = content.replace(/(<[^ >][^>]*>)?/gm, '');
		// Remove all line breaks.
		content = content.replace(/\s\s+/g, ' ');
		return aioseopDecodeHtmlEntities(content.trim());
	}

	/**
	 * The aioseopDecodeHtmlEntities() function.
	 * 
	 * Decodes HTML entities to characters.
	 * 
	 * @since 3.3.0
	 * 
	 * @param string encodedString
	 * @return string
	 */
	function aioseopDecodeHtmlEntities(encodedString) {
		let textArea = document.createElement('textarea');
		textArea.innerHTML = encodedString;
		return textArea.value;
	}

});
