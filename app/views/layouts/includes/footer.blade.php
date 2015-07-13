<footer>
    <div class="seccion gris_oscuro">
        <div class="container cont_footer">
            {{ TransArticle::get("footer.texto") }}
            @section("piedepagina")
            @show
        </div>
    </div>
</footer>

