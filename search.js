function search() {
    const testBookName = document.getElementById('header__search-input').value.trim();

    if (testBookName === "") {
        var resultSearchList = document.getElementById('header__search-list');
        resultSearchList.innerHTML = ""; // Xóa nội dung cũ
        var noResult = document.createElement('li');
        noResult.classList.add('header__search-history-item');
        noResult.textContent = "Không có kết quả tìm kiếm";
        resultSearchList.appendChild(noResult);
        return;
    }

    let testURL = "http://localhost:3000/Shop_project/HienThiKQTK.php?bookName=" + testBookName;
    fetch(testURL)
        .then(response => response.json())
        .then(
            data => {
                console.log(data);
                var resultSearchList = document.getElementById('header__search-list');
                resultSearchList.innerHTML = ""; // Xóa nội dung cũ

                if (data.length > 0) {
                    data.forEach(book => {
                        var listItem = document.createElement('li');
                        listItem.classList.add('header__search-history-item');
                        var link = document.createElement('a');
                        link.href = "detailProduct.php?id=" + book.bookID;
                        link.textContent = book.bookName; // Thay thế bookName bằng tên cột tương ứng trong dữ liệu
                        listItem.appendChild(link);
                        resultSearchList.appendChild(listItem);
                    });
                } else {
                    var noResult = document.createElement('li');
                    noResult.classList.add('header__search-history-item');
                    noResult.textContent = "Không có kết quả tìm kiếm";
                    resultSearchList.appendChild(noResult);
                }
            }
        );
}

function showResultSearch() {
    document.querySelector('.header__search-history').style.display = 'Block';
}

