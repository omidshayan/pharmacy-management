<div id="myModal" class="modal">
    <div class="modal-content-help border-radius">
        <br>
        <h4 class="mb10 color-orange"><?= $help_title ?></h4>
        <hr class="hr mb10">
        <p><?= $help_content ?></p>
        <div class="cancel-text mb20">
        </div>
        <div id="cancelBtn" class="color btn p5 w100 m10 center border-none border-hover fs14">باشه</div>
    </div>
</div>

<script>
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("openModalBtn");
    var span = document.getElementsByClassName("close")[0];
    btn.onclick = function() {
        modal.style.display = "block";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    document.getElementById("cancelBtn").onclick = function() {
        modal.style.display = "none";
    }
    document.getElementById("confirmBtn").onclick = function() {
        var email = document.getElementById("emailInput").value;
        modal.style.display = "none";
    }
</script>