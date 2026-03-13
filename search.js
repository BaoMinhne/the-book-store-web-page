let searchDebounceTimer = null;
let activeSearchController = null;

function renderSearchMessage(message) {
    const resultSearchList = document.getElementById('header__search-list');
    if (!resultSearchList) {
        return;
    }

    resultSearchList.innerHTML = '';

    const item = document.createElement('li');
    item.classList.add('header__search-history-item');
    item.textContent = message;
    resultSearchList.appendChild(item);
}

function renderSearchResults(data) {
    const resultSearchList = document.getElementById('header__search-list');
    if (!resultSearchList) {
        return;
    }

    resultSearchList.innerHTML = '';

    if (!Array.isArray(data) || data.length === 0) {
        renderSearchMessage('Không có kết quả tìm kiếm');
        return;
    }

    data.forEach((book) => {
        if (!book || !book.bookID || !book.bookName) {
            return;
        }

        const listItem = document.createElement('li');
        listItem.classList.add('header__search-history-item');

        const link = document.createElement('a');
        link.href = 'detailProduct.php?id=' + book.bookID;
        link.textContent = book.bookName;

        listItem.appendChild(link);
        resultSearchList.appendChild(listItem);
    });
}

function parseSearchResponse(rawText) {
    try {
        return JSON.parse(rawText);
    } catch (_error) {
        // fallback khi response lẫn warning/notice trước JSON
        const firstBracket = rawText.indexOf('[');
        const lastBracket = rawText.lastIndexOf(']');

        if (firstBracket !== -1 && lastBracket !== -1 && firstBracket <= lastBracket) {
            const jsonChunk = rawText.slice(firstBracket, lastBracket + 1);
            return JSON.parse(jsonChunk);
        }

        return [];
    }
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

        fetch(searchURL, {
            signal: activeSearchController.signal,
            headers: {
                Accept: 'application/json',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Search request failed');
                }

                return response.text();
            })
            .then((rawText) => {
                const data = parseSearchResponse(rawText);
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
