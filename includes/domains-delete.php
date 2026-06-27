<script>
        const cardBodyItems = document.querySelectorAll('.card-body tbody tr');

        cardBodyItems.forEach(e => {
            const td = document.createElement('td');
            td.style.cssText = `position:relative;padding:10px;`;
            td.innerHTML = "<button class='delete-btn btn btn-danger btn-sm'><i class='fas fa-trash' title='Delete'></i></button>";
            e.insertAdjacentElement('beforeend', td);
            td.querySelector('td .delete-btn').style.cssText = `position:absolute;right:0;margin-right:5px;`;

            const link = e.querySelector('td a').getAttribute('href');
            const domainName = e.querySelector('td a').innerHTML;
            const newLink = link.slice(19);

            td.querySelector('td .delete-btn').setAttribute('data-delete', newLink);

            td.querySelectorAll('td .delete-btn').forEach(c => {
                c.addEventListener('click', () => {
                    let accept = confirm("Are you sure you want to delete '" + domainName + "' ?");
                    if (accept === true) {
                        const deleteLink = c.getAttribute('data-delete');
                        window.location = window.location + "?deleteid=" + deleteLink;
                        setTimeout(() => { window.location = "dashboard.php"; }, 50);
                    }
                });
            });
        });
</script>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['deleteid'])) {
        $id = (int)$_GET['deleteid'];
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM adm_domains WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
?>
