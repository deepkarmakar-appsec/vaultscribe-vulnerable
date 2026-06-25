const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const fileList = document.getElementById('fileList');
const uploadBtn = document.getElementById('uploadBtn');

const statCount = document.getElementById('statCount');
const statSize = document.getElementById('statSize');
const statReady = document.getElementById('statReady');

let files = [];

document.getElementById('browseBtn').addEventListener('click', () => {
    fileInput.click();
});

dropZone.addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', (e) => {
    addFiles(Array.from(e.target.files));
});

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');

    addFiles(Array.from(e.dataTransfer.files));
});

function formatSize(bytes) {

    if (bytes < 1024) {
        return bytes + ' B';
    }

    if (bytes < 1024 * 1024) {
        return (bytes / 1024).toFixed(1) + ' KB';
    }

    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function updateStats() {

    const totalSize = files.reduce((sum, file) => {
        return sum + file.size;
    }, 0);

    statCount.textContent = files.length;
    statSize.textContent = formatSize(totalSize);

    statReady.textContent =
        files.length > 0
            ? 'Ready'
            : '—';

    uploadBtn.disabled = files.length === 0;
}

function addFiles(newFiles) {

    const allowedTypes = [
        'image/jpeg',
        'image/webp'
    ];

    newFiles.forEach(file => {

        if (!allowedTypes.includes(file.type)) {

            alert(
                'Only JPG, JPEG and WEBP files are allowed'
            );

            return;
        }

        const exists = files.find(
            f =>
                f.name === file.name &&
                f.size === file.size
        );

        if (!exists) {
            files.push(file);
        }
    });

    syncInputFiles();
    renderFiles();
    updateStats();
}

function syncInputFiles() {

    const dt = new DataTransfer();

    files.forEach(file => {
        dt.items.add(file);
    });

    fileInput.files = dt.files;
}

function renderFiles() {

    fileList.innerHTML = '';

    files.forEach((file, index) => {

        const div = document.createElement('div');

        div.className = 'file-item';

        div.innerHTML = `
            <div class="file-info">
                <div class="file-name">
                    ${file.name}
                </div>

                <div class="file-meta">
                    ${formatSize(file.size)}
                </div>
            </div>

            <button
                type="button"
                class="file-remove"
                data-index="${index}">
                ✕
            </button>
        `;

        fileList.appendChild(div);
    });

    document
        .querySelectorAll('.file-remove')
        .forEach(btn => {

            btn.addEventListener('click', () => {

                const index =
                    parseInt(
                        btn.dataset.index
                    );

                files.splice(index, 1);

                syncInputFiles();
                renderFiles();
                updateStats();
            });
        });
}

updateStats();