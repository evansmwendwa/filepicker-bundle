class Filepicker {

    constructor() {
        this.browseEndpoint = '/evans-filepicker-list';
        this.uploadEndpoint = '/evans-file-picker-upload';
        this.selectedFile = '';
        this.files = [];
        this.filesLoaded = false;
        this.modalSelector = '';
    }

    select(selector) {
        return document.querySelector(selector);
    }

    upload(data, updateProgress, transferComplete, transferFailed, transferCanceled) {
        let xhr  = new XMLHttpRequest();
        let form = new FormData();
        let method = 'POST';

        this.forEach(data.data, (key, value) => {
            form.append(key, value);
        });

        for(let i = 0; i < data.files.length; i++) {
            form.append('chooserfiles['+ i +']', data.files[i]);
        }

        xhr.addEventListener("progress", updateProgress);
        xhr.addEventListener("load", transferComplete);
        xhr.addEventListener("error", transferFailed);
        xhr.addEventListener("abort", transferCanceled);

        xhr.open(method, this.uploadEndpoint, true);
        xhr.send(form);
    }

    isSuccessStatus(status) {
        return (status >= 200 && status < 300) || status === 304;
    }

    forEach(obj, callback) {
        for (var i in obj) {
            if (obj.hasOwnProperty(i)) {
                callback(i, obj[i]);
            }
        }
    }

    /**
    * @param modalSelector valid css selector for the modal.
    * @param input Will be passed back to the callback as an argument.
    * @param callback(file, input) Will be called after insert is clicked.
    * @returns null - execute return via a callback
    */
    pickFiles(modalSelector, input, callback) {
        this.modalSelector = modalSelector;
        const modal = this.select(modalSelector);

        // handle media insert
        const insertButton = this.select(modalSelector + ' .insert');

        if(insertButton) {
            insertButton.onclick = (e) => {
                this.closeModal(modal);
                callback(this.selectedFile, input);
            }
        }

        // handle close modal
        const closeBtn = this.select(modalSelector + ' .icon-close');

        if(closeBtn) {
            closeBtn.onclick = (e) => {
                this.selectedFile = '';
                this.closeModal(modal);
            }
        }

        this.openModal(modal);
    }

    openModal(modal) {
        modal.classList.add('visible');
        this.loadFiles();
    }

    closeModal(modal) {
        modal.classList.remove('visible');
    }

    showMessage(message) {
        const msgBox = document.querySelector('#filepicker .msg-box');
        msgBox.innerHTML = message;
        msgBox.classList.remove('hidden');
    }

    clearMessage() {
        const msgBox = document.querySelector('#filepicker .msg-box');
        msgBox.innerHTML = '';
        msgBox.classList.add('hidden');
    }

    displayFiles() {
        try {
            const filesContainer = this.select(this.modalSelector + ' .file-list');

            if(filesContainer) {
                filesContainer.innerHTML = '';
                this.files.forEach((item) =>  {
                    const thumbnail = document.createElement('img');
                    thumbnail.src = item.thumbnail_path;

                    const thumbContainer = document.createElement('div');
                    thumbContainer.dataset.img = item.url;
                    thumbContainer.classList.add('file-picker-thumbnail');
                    thumbContainer.appendChild(thumbnail);

                    thumbContainer.onclick = (e) => {
                        let target = e.currentTarget;
                        this.selectedFile = target.dataset.img;
                        this.clearMediaSelection();
                        target.classList.add('active');
                    }

                    filesContainer.appendChild(thumbContainer);
                });
            }
        } catch (e) {
            console.log(e.message);
        }

    }

    loadFiles(refresh) {
        refresh = refresh || false;

        if(!this.filesLoaded || refresh) {
            $.get(this.browseEndpoint,null,(data, status) => {
                this.files = data.files;
                this.filesLoaded = true;
                if(data.files.length < 1) {
                    this.showMessage('No files found.');
                } else {
                    this.displayFiles();
                }
            },'json');
        }
    }

    clearMediaSelection() {
        const elems = document.querySelectorAll(this.modalSelector + ' .file-picker-thumbnail');
        elems.forEach((item) => {
            item.classList.remove('active');
        })
    }
}

document.addEventListener("DOMContentLoaded", function(event) {
  const filePicker = new Filepicker();
  const pickers = document.querySelectorAll('.open-file-chooser');

  pickers.forEach((item) => {
      item.onclick = (e) => {
          filePicker.pickFiles('#filepicker-modal', e.currentTarget, (file, button) => {
              const id = button.dataset.id;
              const targetInput = document.querySelector('#'+ id);
              if(targetInput) {
                  targetInput.value = file;
              }
              const targetPreview = document.querySelector('#chooser-preview-'+ id);
              if(targetPreview) {
                  targetPreview.src = file;
              }
          })
      }
  })
});
