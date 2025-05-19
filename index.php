<!DOCTYPE html>
<html lang="en">
    <?php 
    require_once './utils/head.php'
    ?>
    <style>
    #load-more-btn {
    color: #fff;
    border: none;
    cursor: pointer; 
}

#load-more-btn:hover {
    background-color:rgb(245, 57, 57);
    color:'white'
}
 #load-more-btn {

    width: 200px;
    height: 45px;
    background-color:rgb(72, 223, 185);
    border-radius:10px;
}
#show_more
{
    display:flex;
    justify-content:center;
    margin:10px;
    margin-bottom:50px;
    
}
    
    </style>
      <link href="styles.css?v=./css/responsive.css" rel="stylesheet">
      <link href="styles.css?v=./css/style.css" rel="stylesheet">
      <link href="styles.css?v=./css/single.css" rel="stylesheet">
<body>
<?php 
    require_once './utils/header.php'
    ?>



  <?php
    require_once './categories/Two_Section.php';

    ?>


<!-- Container for additional categories -->
<div id="additional-categories" style="display: none;">
    <?php
    require_once './categories/Rest_Section.php';
    ?>
</div>

<!-- Load More Button -->
 <div id="show_more">

     <button id="load-more-btn" class="glow-on-hover" onclick="toggleCategories()">Load More Categories</button>
 </div>

<!--
<script>
document.addEventListener("DOMContentLoaded", function () {
    const idsToControl = [
        "Milk-Based", "Fried", "Nut-Based", "Flour-Based",
        "Halwa", "Dry", "Syrupy", "Festival", "Modern"
    ];

    // Store the current scroll position

    function handleCarouselClickNext(button) {
        button.addEventListener("click", function () {
            let lastScrollY = window.scrollY;
            const fashionSection = this.closest('.fashion_section');
            if (!fashionSection || !fashionSection.id) return;
            const currentId = fashionSection.id;

            // 2) Hide all but current
            idsToControl.forEach(id => {
                const section = document.getElementById(id);
                if (!section) return;
                section.style.display = (id === currentId) ? "block" : "none";
            });
        });
    }

    function handleCarouselClickPrev(button) {
        button.addEventListener("click", function () {
            // 3) Unhide everything
            idsToControl.forEach(id => {
                const section = document.getElementById(id);
                if (!section) return;
                section.style.display = "block";
            });

            // 4) Restore to the oldscroll
            window.scrollTo(0, lastScrollY);
        });
    }

    // Attach handlers
    document.querySelectorAll('.carousel-control-next')
            .forEach(btn => handleCarouselClickNext(btn));
    document.querySelectorAll('.carousel-control-prev')
            .forEach(btn => handleCarouselClickPrev(btn));
});
</script>
-->

<script>
function toggleCategories() {
    var additionalCategories = document.getElementById('additional-categories');
    var loadMoreBtn = document.getElementById('load-more-btn');

    if (additionalCategories.style.display === 'none') {
        additionalCategories.style.display = 'block';
        loadMoreBtn.textContent = 'Show Less Categories';
    } else {
        additionalCategories.style.display = 'none';
        loadMoreBtn.textContent = 'Load More Categories';
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.add-to-cart').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();

      const pid = link.dataset.id;

      fetch(`utils/cart_logo.php?action=add&id=${pid}`, {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'  // Important: triggers AJAX response
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success && data.newCount !== undefined) {
          const badge = document.getElementById('cart_count');
          if (badge) {
            badge.textContent = data.newCount;
          }
        } else {
          console.error("Cart update failed", data);
        }
      })
      .catch(error => console.error("AJAX Error:", error));
    });
  });
});
</script>

<?php 

    require_once './utils/footer.php'
    ?>
</body>
</html>
