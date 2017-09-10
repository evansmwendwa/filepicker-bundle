CKEDITOR.plugins.add( 'evans_file_picker', {
    icons: 'evans_file_picker',
    hidpi: true,
    init: function(editor) {
        editor.addCommand('evans_file_picker', {
            exec: function(editor) {
                const filePicker = new Filepicker();
                filePicker.pickFiles('#filepicker-modal', null, (file, elem) => {
                    editor.insertHtml( '<img src="' + file + '" alt="" />' );
                })
            }
        });

        editor.ui.addButton && editor.ui.addButton('evans_file_picker', {
			label: 'Pick Image',
			command: 'evans_file_picker',
			toolbar: 'insert,0'
		});
    }
});
