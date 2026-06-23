
if (document.querySelector('.search-bar') !== null) {

    const searchBar = document.querySelector('.search-bar');


    searchBar.addEventListener('keyup', e => {


        console.log(searchBar.value);

        let searchresults = searchBar.value;

        document.querySelectorAll('table tbody tr').forEach(x => {


            if (x.querySelector('a') !== null) {


                const names = x.querySelector('a').innerText;
                const newnames = names.toLowerCase();

                if (newnames.includes(searchBar.value)) {
                    x.classList.remove('d-none');
                } else {
                    x.classList.add('d-none');
                };

                if (searchBar.value == "") {
                    x.classList.remove('d-none');
                }


            };


        })


    });

}