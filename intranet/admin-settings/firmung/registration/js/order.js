const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
    v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
    )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

// do the work...
document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {

    // sort
    const table = th.closest('table');
    Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
        .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
        .forEach(tr => document.getElementById("regData").appendChild(tr) );

    //icon
    document.querySelectorAll('th').forEach(element => {
        element.classList.remove("order-selected");
        if(element != th) {
            element.classList.remove("desc");
        }
    });

    th.classList.add("order-selected");

    if(!this.asc) {
        th.classList.add("desc");
    } else {
        th.classList.remove("desc");
    }
})));