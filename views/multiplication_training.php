<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <title>My school</title>
    <link rel="stylesheet" href="/public/css/index.css?v=3" >
</head>
<body>
<audio src="/public/audio/correct_answer.mp3" id="correct_answer"></audio>
<audio src="/public/audio/wrong_answer.mp3" id="wrong_answer"></audio>
<main class="container">
    <header>
        <div style="text-align: center">My School</div>
        <div class="menu">
            <a class="menu-item" href="/">Տուն</a>
            <a class="menu-item" href="/multiplication/table">Աղյուսակ</a>
        </div>
    </header>
    <button id="begin">Սկսել</button>
    <div id="score"><span>100</span> / <span>0</span> / <span>0</span></div>
    <div class="num-container">
        <span id="num"></span>
        <span id="answer"><span id="answer_text"></span></span>
        <span id="answer_animation"></span>
    </div>
    <div class="calc">
        <div class="calc-row">
            <div class="calc-column">0</div>
            <div class="calc-column">1</div>
            <div class="calc-column">2</div>
            <div class="calc-column">3</div>
            <div class="calc-column">4</div>
        </div>
        <div class="calc-row">
            <div class="calc-column">5</div>
            <div class="calc-column">6</div>
            <div class="calc-column">7</div>
            <div class="calc-column">8</div>
            <div class="calc-column">9</div>
        </div>
        <div class="calc-row">
            <div class="calc-column">&larr;</div>
        </div>
    </div>
</main>
<footer>

</footer>

<script>
    window.addEventListener('load', function () {
        let numContainer = document.querySelector('.num-container');
        let calc = document.querySelector('.calc');
        let scores = document.querySelectorAll('#score>span');
        let num = document.getElementById('num');
        let begin = document.getElementById('begin');
        let answer = document.getElementById('answer');
        let answer_text = document.getElementById('answer_text');
        let correct_answer = document.getElementById('correct_answer');
        let wrong_answer = document.getElementById('wrong_answer');
        let answer_animation = document.getElementById('answer_animation');
        //---------variables--------------------------------------------------------
        let trueSVG = '<svg width="100%" height="100%" viewBox="0 0 190 190">'
            + '<path fill="none" stroke-width="40" stroke-linecap="round" stroke-linejoin="round"' +
            ' stroke-dasharray="0 300" stroke-dashoffset="0" stroke="var(--c1)"' +
            ' d="M20 70c22,28 38,63 50,100 20,-55 51,-97 100,-150">' +
            ' <animate from="0 300" to="300 0" dur="300ms"' +
            ' repeatCount="1" begin="0s" attributeName="stroke-dasharray"' +
            ' fill="freeze"/></path></svg>';
        let falseSVG = '<svg width="100%" height="100%" viewBox="0 0 190 190">'
            + '<path fill="none" stroke-width="40" stroke-linecap="round" stroke-linejoin="round"' +
            ' stroke-dasharray="300 0" stroke-dashoffset="20" stroke="var(--c4)"' +
            ' d="M20 20 C 40 65, 125 150, 170 170">' +
            ' <animate from="0 300" to="300 0" dur="300ms"' +
            ' repeatCount="1" begin="0s" attributeName="stroke-dasharray"' +
            ' fill="freeze"/></path>' +
            '<path fill="none" stroke-width="40" stroke-linecap="round" stroke-linejoin="round"' +
            ' stroke-dasharray="0 300" stroke-dashoffset="20" stroke="var(--c4)"' +
            ' d="M20 170 C 40 125, 145 65, 170 20">' +
            ' <animate from="0 300" to="300 0" dur="300ms"' +
            ' repeatCount="1" begin="200ms" attributeName="stroke-dasharray"' +
            ' fill="freeze"/>' +
            '</path>' +
            '</svg>';
        //answer_animation.innerHTML = falseSVG;numContainer.style.display = 'flex';//temporary
        let n1, n2;
        let nTrue = 0;
        let nFalse = 0;
        let answerText = "";
        let realAnswer = "";
        let allowClick = true;
        let numbers = [];
        for(let i = 2; i <= 9; i++){
            for(let j = i; j <= 9; j++){
                numbers.push([i, j]);
            }
        }
        let len = numbers.length;
        let len1 = len;
        scores[0].innerHTML = len.toString();
        begin.addEventListener('click', function () {
            fBegin();
        });
        let iToNum = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'left'];
        document.querySelectorAll('.calc .calc-column').forEach((btn, i) => {
            btn.addEventListener('click',  () => {
                if(!allowClick){
                    return false;
                }
                let n3 = iToNum[i];
                if(n3 === 'left'){
                    answerText = answerText.slice(0, answerText.length - 1);
                }else{
                    answerText += n3;
                }
                toCheck();
            });
        });
        function sleep(t) {
            return new Promise((resolve) => {
                setTimeout(resolve, t);
            });
        }
        function makeTrue(){
            nTrue++;
            answer_animation.innerHTML = trueSVG;
            correct_answer.play();
            setTimeout(function(){
                answerText = "";
                answer_text.innerHTML = answerText;
                answer_animation.innerHTML = "";
                fGenerate();
            }, 700);
        }
        async function makeFalse(){
            nFalse++;
            answer_animation.innerHTML = falseSVG;
            wrong_answer.play();
            await sleep(700);
            answer_text.classList.add('answer_text_0');
            await sleep(300);
            answer_text.innerHTML = realAnswer;
            answer_text.classList.remove('answer_text_0');
            answer_text.classList.add('answer_text_1');
            await sleep(1300);
            answer_text.classList.remove('answer_text_1');
            answerText = "";
            answer_text.innerHTML = answerText;
            answer_animation.innerHTML = "";
            fGenerate();
        }
        function toCheck() {
            realAnswer = (n1 * n2).toString();
            answer_text.innerHTML = answerText;
            if(answerText.length < realAnswer.length){
                return 0;
            }
            allowClick = false;
            if(answerText === realAnswer){
                makeTrue();
            }else{
                makeFalse();
            }
        }
        function fBegin() {
            len1 = len;
            nTrue = 0;
            nFalse = 0;
            answerText = "";
            numContainer.style.display = 'flex';
            calc.style.display = 'flex';
            answer_text.innerHTML = answerText;
            fGenerate();
        }
        function fGenerate() {
            allowClick = true;
            scores[0].innerHTML = len1.toString();
            scores[1].innerHTML = nTrue.toString();
            scores[2].innerHTML = nFalse.toString();
            if(len1 < 1){
                num.innerHTML = "";
                numContainer.style.display = 'none';
                calc.style.display = 'none';
                return 0;
            }
            let r = Math.floor(Math.random() * len1);
            n1 = numbers[r][0];
            n2 = numbers[r][1];
            num.innerHTML = n1 + " &times; " + n2 + " =&nbsp;";
            len1 --;
            let reserve = numbers[len1];
            numbers[len1] = numbers[r];
            numbers[r] = reserve;

        }

    });
</script>
</body>
</html>