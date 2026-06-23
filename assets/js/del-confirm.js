if(document.querySelector('.del-confirm')!==null){

    const delBtn = document.querySelector('.del-confirm');

    delBtn.addEventListener('click',e=>{
 
        if(confirm('Are you sure you want to delete this?')!==true){
            e.preventDefault();
        };
    });
};