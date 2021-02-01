var modal_auth = document.getElementById('modal_auth');
var modal_auth_btn = document.getElementById("modal_auth_btn");
var close_auth = document.getElementById("close_auth");

if (modal_auth_btn) {
    modal_auth_btn.onclick = function () {
        modal_auth.style.display = "block";
    }
}
if (close_auth) {
    close_auth.onclick = function () {
        modal_auth.style.display = "none";
    }
}
//////////
// Настройка модального окна
function modal(buttonID, modalWrp, buttonType) {
    let btn = document.querySelector(`#${buttonID}`);
    let modalBox = document.querySelector(`#${modalWrp}`);
    switch (buttonType) {
        case "editCol":
            btn.onclick = function () {
                let selectedItem = document.querySelector(".selected-column");
                if (selectedItem) {
                    let nameToChange = selectedItem.getAttribute("data-cell-id");
                    document.querySelector("#newColumnName").value = nameToChange.replace(/_/g," ")
                    // document.querySelector("#currentName").innerHTML = nameToChange;
                    modalBox.style.display = "block";
                } else showAnnouncement("You did not select a column", "red");
                window.onclick = function (event) {
                    if (event.target == modalBox) {
                        close_dialog_box(modalWrp);
                    }
                }
            }
            break;
        case "addCol":
            btn.onclick = function () {
                modalBox.style.display = "block";
                window.onclick = function (event) {
                    if (event.target == modalBox) {
                        close_dialog_box(modalWrp);
                    }
                }
            }
            break;
        case "editTerm":
            btn.onclick = function () {
                let selectedTerm = document.querySelector(".selected-term");
                if (selectedTerm) {
                    let request = new XMLHttpRequest();
                    request.open("POST", "index/makeEditTerm", true);
                    request.onload = function () {
                        document.querySelector("#editTermWrp").innerHTML = this.responseText;
                    }
                    let editTermId = {
                        id: selectedTerm.id
                    }
                    request.send(JSON.stringify(editTermId));
                    modalBox.style.display = "block";

                } else showAnnouncement("You did not select a term", "red");
                window.onclick = function (event) {
                    if (event.target == modalBox) {
                        close_dialog_box(modalWrp);
                    }
                }
            }
            break;
        case "addTerm":
            btn.onclick = function () {
                let requestAddTerm = new XMLHttpRequest();
                requestAddTerm.open("POST", "index/makeAddTerm", true);
                requestAddTerm.onload = function () {
                    document.querySelector("#addTermWrp").innerHTML = this.responseText;
                }
                requestAddTerm.send();

                modalBox.style.display = "block";
                window.onclick = function (event) {
                    if (event.target == modalBox) {
                        close_dialog_box(modalWrp);
                    }
                }
            }
            break;
        // default:
        //     btn.onclick = function () {
        //         modalBox.style.display = "block";
        //     }
    }
}

// Закрыть модально окно
function close_dialog_box(id) {
    document.querySelector("#" + id).style.display = "none";
}

// Модальное окно добавить колонку
modal("add-column-button", "addColumn", "addCol");


// Модальное окно Изменить колонку
modal("edit-column-button", "modal_editCol", "editCol");


// Модальное окно добавить строку
modal("add-term-button", "modal_addTerm", "addTerm")
//
// Модальное окно Изменить строку
modal("edit-term-button", "modal_editRow", "editTerm")

// ///////////////////

function makeHttpRequest(method, uri, request_data, callback) {
    let request = new XMLHttpRequest();
    //Настройка запроса
    request.open(method, uri, true);

    //Функция, которая будем выполняться при получении ответа на запрос
    request.onload = function () {
        if (this.status != 501) {
            callback(this.responseText);
        } else {
            showAnnouncement(this.responseText, "red");
        }
    }
    //Отправка запроса
    request.send(JSON.stringify(request_data));
}

function showAnnouncement(text, backgroundColor) {
    let announcement = document.createElement("div");
    announcement.setAttribute("class", `announcement ${backgroundColor}`);
    //
    let announcement_text = document.createElement("p");
    announcement_text.setAttribute("class", "announcement-text");
    announcement_text.innerHTML = text;
    announcement.appendChild(announcement_text);
    document.querySelector(".announcement-wrp").appendChild(announcement);

    announcement.animate({opacity: '0'}, 4000);

    setTimeout(function () {
        announcement.remove();
    }, 3800);
}

function isColumnNameUnique(new_col_value) {
    let columns = document.querySelectorAll(".header-title");
    for (let i = 0; i < columns.length; i++) {
        if (new_col_value.toLowerCase() == columns[i].innerText.toLowerCase()) {
            return false;
        }
    }
    return true;
}

function renderColumn(columnName) {
    let th_title = document.createElement("th");
    th_title.innerHTML = columnName;
    th_title.addEventListener("click", selectColumn);
    th_title.setAttribute("data-cell-id", columnName.replace(/ /g, "_"))
    document.querySelector("#main_row").appendChild(th_title);
    document.querySelectorAll(".table_row").forEach(function (item) {
        let td = document.createElement("td");
        td.setAttribute("data-cell-id", columnName.replace(/ /g, "_"));
        item.appendChild(td);
    });
}

function selectColumn() {
    let columnTitle = event.currentTarget; // th на который кликнули
    let attrName = columnTitle.getAttribute("data-cell-id");// получить значение аттрибута(имя кол) th

    if (columnTitle.classList.contains("selected-column")) {
        document.querySelectorAll(`[data-cell-id="${attrName}"]`).forEach(function (item) {
            item.classList.remove("selected-column");
        });
    } else {
        document.querySelectorAll(".selected-column").forEach(function (item) {
            item.classList.remove("selected-column")
        })
        let selectedRow = document.querySelector(".selected-term");
        if (selectedRow) {
            selectedRow.classList.remove("selected-term")
        }
        document.querySelectorAll(`[data-cell-id="${attrName}"]`).forEach(function (item) {
            item.classList.add("selected-column");
        });
    }
}

//Событие выделить колонку
document.querySelectorAll("#main_row th").forEach(function (item) {
    item.addEventListener("click", selectColumn);
});

//Добавить колонку /////////////////////////////////////////////
function add_col() {
    //Создаем XMLHttpRequest объект
    let new_col_value = document.getElementById("column-name").value;
    if (new_col_value === "") {
        showAnnouncement("Column name is empty", "red")
    } else if (!isColumnNameUnique(new_col_value)) {
        showAnnouncement("Column name is already in use", "red");
    } else {
        let new_column = {
            name: new_col_value.replace(/ /g, "_")//имя нового столбца
        }
        makeHttpRequest("POST", "/index/add_column", new_column, function () {
            renderColumn(new_col_value);
            document.getElementById("column-name").value = '';
            close_dialog_box("addColumn");
            showAnnouncement("Column was successfully added!", "green");
        })
    }
}

// Удалить колонку /////////////////////////////
function deleteColumn() {
    let selectedColumn = document.querySelector("th.selected-column");
    if (!selectedColumn) {
        showAnnouncement("You did not select a column", "red")
    } else {
        // let col_name = selectedColumn.getAttribute("data-cell-id");
        let col_name = {
            name: selectedColumn.getAttribute("data-cell-id")
        }
        makeHttpRequest("POST", "index/deleteColumn", col_name, function () {
            showAnnouncement("Column was successfully deleted", "green");
            renderDeletedColumn();
        })
    }
}

function renderDeletedColumn() {
    document.querySelectorAll(".selected-column").forEach(function (item) {
        item.remove();
    })
}

////////////////////////////////////
// Редактировать имя колонки
function editColumnName() {
    let oldName = document.querySelector(".selected-column").getAttribute("data-cell-id");
    let name = document.querySelector("#newColumnName").value;
    if(name == ''){
        showAnnouncement("Column name is required","red")
    } else {
        let newName = {
            name: name,
            oldName: oldName
        }
        makeHttpRequest("POST", "index/editColumn", newName, function () {
            document.querySelector("th.selected-column").innerHTML = name;
            document.getElementById("newColumnName").value = '';
            document.querySelectorAll(".selected-column").forEach(function (item) {
                item.setAttribute("data-cell-id", name);
            })
            close_dialog_box("modal_editCol");
            showAnnouncement("Column was successfully edited!", "green");
        })
    }
}


//Выделение строки
function select_term() {
    let row = event.currentTarget;
    if (row.classList.contains("selected-term")) {
        row.classList.remove("selected-term");
    } else {
        document.querySelectorAll(".selected-term").forEach((element) => {
            element.classList.remove("selected-term");
        });
        let selectedColumn = document.querySelector(".selected-column");
        if (selectedColumn) {
            document.querySelectorAll(".selected-column").forEach(function (item) {
                item.classList.remove("selected-column")
            })
        }
        row.classList.add("selected-term");
    }
}

// Событие выделения строки
let rows = document.querySelectorAll(".table_row");
for (let i = 0; i < rows.length; i++) {
    rows[i].addEventListener("click", select_term);
}

// Удаление строки
function deleteTerm(){
    let selectedTerm = document.querySelector(".selected-term");
    if (!selectedTerm) {
        showAnnouncement("You did not select a term", "red")
    } else {
            let term_id = {
                name: selectedTerm.id
            }
            makeHttpRequest("POST", "index/deleteTerm", term_id, function () {
                showAnnouncement("Term was successfully deleted", "green");
                renderDeletedTerm();
            })
        }
}
function renderDeletedTerm(){
    document.querySelector(".selected-term").remove();
}

// Редактирование строки
function editTerm(){
    let arrStr = [];
    let newCells = [];
    let arr_inputsToEdit = document.querySelectorAll(".inputEditTerm");
    arr_inputsToEdit.forEach(function (item){
        arrStr.push("`" + item.id.replace("term-", "")+"` = '" + item.value + "'");
        newCells.push(item.value);
    })
    let selectedId = document.querySelector(".selected-term").id
    let str = arrStr.join(", ");
    let sqlEditTerm = {
        id: selectedId,
        str: str
    }
    // console.log(sqlEditTerm)
    makeHttpRequest("POST","index/editTerm",sqlEditTerm,function (){
        showAnnouncement("Term was successfully edited", "green");
        close_dialog_box("modal_editRow");
        let selectedTDs = document.querySelectorAll(".selected-term td");
        for(let i = 0; i<newCells.length; i++){
            selectedTDs[i].innerHTML = newCells[i];
        }
    })
}

// Добавление строки
function addTerm(){
    let namesToAdd = [];
    let valuesToAdd = [];
    let arr_inputsToAdd = document.querySelectorAll(".inputAddTerm");
    arr_inputsToAdd.forEach(function (item){
        namesToAdd.push("`" + item.id.replace("term-", "")+"`");
        valuesToAdd.push("'" + item.value+"'")
    })
    let names = namesToAdd.join(",");
    let values = valuesToAdd.join(",");
    let sqlAddTerm = {
        names: names,
        values: values
    }
    makeHttpRequest("POST","index/addTerm",sqlAddTerm,function (responseText){
        showAnnouncement("Term was successfully added", "green");
        close_dialog_box("modal_addTerm");
        let arrNamesToPast = [];
        let arrValuesToPast = [];
        arr_inputsToAdd.forEach(function (item){
            arrNamesToPast.push(item.id.replace("term-", ""));
            arrValuesToPast.push(item.value)
        })
        let newTR = document.createElement("tr");
        newTR.classList.add("table_row");
        newTR.setAttribute("id", JSON.parse(responseText).id);
        newTR.addEventListener("click", select_term);
        for(let i = 0; i<arrNamesToPast.length; i++){
            let td = document.createElement("td");
            td.innerHTML = arrValuesToPast[i];
            td.setAttribute("data-cell-id", arrNamesToPast[i]);
            newTR.appendChild(td);
        }
        document.querySelector("tbody").appendChild(newTR);
        let selectedTh = document.querySelector(".selected-column")
        if(selectedTh){
            let selectedColumnName = selectedTh.getAttribute("data-cell-id");
            document.querySelectorAll(`[data-cell-id = ${selectedColumnName}]`).forEach(function (item){
                if(!item.classList.contains("selected-column")){
                    item.classList.add("selected-column");
                    item.classList.add("selected-column");
                }
            })

        }
    })
}