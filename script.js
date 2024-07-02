let container_buts = document.querySelectorAll('.container_buts');
let text_header_quiz = document.querySelector('.text_header_quiz')



function PageNumber(name,value){
    let page = document.getElementById(name);
    for(let i =0; container_buts.length > i; i++){
        container_buts[i].style.display = 'none';
    }
    text_header_quiz.innerHTML = value
    page.style.display = 'flex';
    console.log('start')
}