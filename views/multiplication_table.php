<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <title>My school</title>
    <link rel="stylesheet" href="/public/css/index.css?v=3">
    <link rel="stylesheet" href="/public/css/multiplication_table.css?v=3">
</head>
<body>


<main class="container">
    <header>
        <div style="text-align: center">My School</div>
        <div class="menu">
            <a class="menu-item" href="/">Տուն</a>
            <a class="menu-item" href="/multiplication/training">Մարզվել</a>
        </div>
    </header>
    <table class="multiplication-table">
        <thead></thead>
        <tbody>
        </tbody>
    </table>
    <div id="m_t_container"></div>
    <div style="height: 4vmin"></div>


</main>
<footer>

</footer>

<script>

    window.addEventListener('load', function () {
        console.timeEnd("m_t_container");
        console.time("a");
        const tbody = document.querySelector('.multiplication-table tbody');
        let td_arr = [];
        let prev_clicked = null;
        let coordinates_clicked = [-1, -1];
        for (let i = 0; i < 9; i++) {
            td_arr.push([]);
            let tr = document.createElement('tr');
            tbody.appendChild(tr);
            for (let j = 0; j < 9; j++) {
                const td = document.createElement('td');
                td.style.width = '10%';
                if(i === 0 || j === 0){
                    td.style.fontWeight = 'bold';
                    td.style.color = 'var(--c1)';
                }
                td.innerHTML = (i + 1) * (j + 1) + '';
                td_arr[i].push(td);
                tr.appendChild(td);
            }
        }
        td_arr.forEach(function (arr, i) {
            arr.forEach(function (td, j) {
                td.addEventListener('click', function () {
                    if(prev_clicked){
                        let i1 = prev_clicked[0];
                        let j1 = prev_clicked[1];
                        for (let x = 0; x < j1; x++) {
                            td_arr[i1][x].style.backgroundColor = 'unset';
                        }
                        for (let x = 0; x <= i1; x++) {
                            td_arr[x][j1].style.backgroundColor = 'unset';
                        }
                        td_arr[i1][j1].style.color = 'unset';
                    }

                    takeOffCoordinates(i, j);

                    if(i === 0 || j === 0){
                        if(i === 0 && j === 0){
                            prev_clicked = null;
                            return 0;
                        }

                        fCoordinates(i, j);

                        prev_clicked = null;
                        return 0;
                    }

                    for (let x = 0; x < j; x++) {
                        td_arr[i][x].style.backgroundColor = 'var(--c2)';
                    }
                    for (let x = 0; x <= i; x++) {
                        td_arr[x][j].style.backgroundColor = 'var(--c2)';
                    }
                    td_arr[i][j].style.color = 'var(--c4)';
                    prev_clicked = [i, j];
                });
            });
        });
        function takeOffCoordinates(i, j) {
            let i1 = coordinates_clicked[0];
            let j1 = coordinates_clicked[1];
            if(i1 > -1){
                for (let x = 0; x < 9; x++) {
                    td_arr[i1][x].style.backgroundColor = 'unset';
                }
            }
            if(j1 > -1){
                for (let x = 0; x < 9; x++) {
                    td_arr[x][j1].style.backgroundColor = 'unset';
                }
            }
            if(i1 > -1 && j1 > -1){
                td_arr[i1][j1].style.color = 'unset';
            }
            if((i === 0 && j === 0) || (i > 0 && j > 0)){
                coordinates_clicked = [-1, -1];
            }
        }
        function fCoordinates(i, j) {
            if(i === 0){
                coordinates_clicked[1] = j;
            }
            if(j === 0){
                coordinates_clicked[0] = i;
            }
            let i1 = coordinates_clicked[0];
            let j1 = coordinates_clicked[1];
            if(i1 > -1 && j1 > -1){
                for (let x = 0; x < j1; x++) {
                    td_arr[i1][x].style.backgroundColor = 'var(--c2)';
                }
                for (let x = 0; x <= i1; x++) {
                    td_arr[x][j1].style.backgroundColor = 'var(--c2)';
                }
                td_arr[i1][j1].style.color = 'var(--c4)';
            }else if(i1 > -1){
                for (let x = 0; x < 9; x++) {
                    td_arr[i1][x].style.backgroundColor = 'var(--c2)';
                }
            }else if(j1 > -1){
                for (let x = 0; x < 9; x++) {
                    td_arr[x][j1].style.backgroundColor = 'var(--c2)';
                }
            }
        }
        //------m_t_container-------------------------------------------------
        let m_t_container = document.getElementById('m_t_container');
        let m_t_htm = '';
        const hh = '<div style="height: 4vmin"></div>';
        const t1 = '<table class="multiplication-table-1"><thead></thead><tbody>';
        const t2 = '</tbody></table>';
        for(let i = 2; i < 10; i++){
            m_t_htm += hh;
            m_t_htm += t1;

            for(let j = 1; j <= 10; j++){
                m_t_htm += '<tr>';
                m_t_htm += '<td>' + i.toString() + ' &times; ' + j.toString() + ' = ' + (i * j).toString() + '</td>';
                m_t_htm += '<td>' + (i * j).toString() + ' : ' + i.toString() + ' = ' + j.toString() + '</td>';
                m_t_htm += '</tr>';
            }

            m_t_htm += t2;
        }
        m_t_container.innerHTML =  m_t_htm;


        // console.time('b');
        //
        // for (let i = 0; i < 10000; i++){
        //
        // }
        // console.timeEnd('b');

    });
</script>
</body>
</html>