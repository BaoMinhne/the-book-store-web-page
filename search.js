let searchDebounceTimer = null;
let activeSearchController = null;

function renderSearchMessage(message) {
    const resultSearchList = document.getElementById('header__search-list');
    resultSearchList.innerHTML = '';

    const item = document.createElement('li');
    item.classList.add('header__search-history-item');
    item.textContent = message;
    resultSearchList.appendChild(item);
}

function renderSearchResults(data) {
    const resultSearchList = document.getElementById('header__search-list');
    resultSearchList.innerHTML = '';

    if (!Array.isArray(data) || data.length === 0) {
        renderSearchMessage('Không có kết quả tìm kiếm');
        return;
    }

    data.forEach((book) => {
        const listItem = document.createElement('li');
        listItem.classList.add('header__search-history-item');

        const link = document.createElement('a');
        link.href = 'detailProduct.php?id=' + book.bookID;
        link.textContent = book.bookName;

        listItem.appendChild(link);
        resultSearchList.appendChild(listItem);
    });
}

function search() {
    const input = document.getElementById('header__search-input');
    if (!input) {
        return;
    }

    const keyword = input.value.trim();

    clearTimeout(searchDebounceTimer);
    searchDebounceTimer = setTimeout(() => {
        if (activeSearchController) {
            activeSearchController.abort();
        }

        if (keyword === '') {
            renderSearchMessage('Nhập từ khóa để tìm kiếm');
            return;
        }

        activeSearchController = new AbortController();
        const searchURL = `HienThiKQTK.php?bookName=${encodeURIComponent(keyword)}`;

        fetch(searchURL, { signal: activeSearchController.signal })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Search request failed');
                }
                return response.json();
            })
            .then((data) => {
                renderSearchResults(data);
            })
            .catch((error) => {
                if (error.name === 'AbortError') {
                    return;
                }
                renderSearchMessage('Không thể tải dữ liệu tìm kiếm');
            });
    }, 250);
}

function showResultSearch() {
    const searchResultPanel = document.querySelector('.header__search-history');
    if (searchResultPanel) {
        searchResultPanel.style.display = 'block';
    }
}
