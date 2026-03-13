function search() {
    const testBookName = document.getElementById('header__search-input').value.trim();

    const resultSearchList = document.getElementById('header__search-list');
    resultSearchList.innerHTML = '';

    if (testBookName === '') {
        const noResult = document.createElement('li');
        noResult.classList.add('header__search-history-item');
        noResult.textContent = 'Không có kết quả tìm kiếm';
        resultSearchList.appendChild(noResult);
        return;
    }

    const testURL = `HienThiKQTK.php?bookName=${encodeURIComponent(testBookName)}`;
    fetch(testURL)
        .then(response => response.json())
        .then(data => {
            resultSearchList.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(book => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('header__search-history-item');
                    const link = document.createElement('a');
                    link.href = 'detailProduct.php?id=' + book.bookID;
                    link.textContent = book.bookName;
                    listItem.appendChild(link);
                    resultSearchList.appendChild(listItem);
                });
            } else {
                const noResult = document.createElement('li');
                noResult.classList.add('header__search-history-item');
                noResult.textContent = 'Không có kết quả tìm kiếm';
                resultSearchList.appendChild(noResult);
            }
        })
        .catch(() => {
            const noResult = document.createElement('li');
            noResult.classList.add('header__search-history-item');
            noResult.textContent = 'Không thể tải dữ liệu tìm kiếm';
            resultSearchList.appendChild(noResult);
        });
}

function showResultSearch() {
    document.querySelector('.header__search-history').style.display = 'Block';
}
