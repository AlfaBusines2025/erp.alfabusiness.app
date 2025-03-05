<nav class="dash-sidebar light-sidebar {{ empty($company_settings['site_transparent']) || $company_settings['site_transparent'] == 'on' ? 'transprent-bg' : '' }}">
    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="{{ route('home') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ get_file(sidebar_logo()) }}{{ '?' . time() }}" alt="" class="logo logo-lg" />
                {{-- <img src="{{ get_file(sidebar_logo()) }}{{ '?' . time() }}" alt="" class="logo logo-sm" /> --}}
            </a>
        </div>
        {{-- sidebar search --}}
        <div class="px-3 sidebar-search">
            <div class="search-container">
                <i class="ti ti-search search-icon"></i>
                <input type="text"
                    class="form-control form-control-sm sidebar-search-input search-input"
                    placeholder="{{ __('Search . . .') }}" aria-label="Search" />
            </div>
        </div>
        @if(!empty($company_settings['category_wise_sidemenu']) && $company_settings['category_wise_sidemenu'] == 'on')
          <div class="tab-container">
            <div class="tab-sidemenu">
              <ul class="dash-tab-link nav flex-column" role="tablist" id="dash-layout-submenus">
              </ul>
            </div>
            <div class="tab-link">
              <div class="navbar-content">



                <div class="tab-content" id="dash-layout-tab">
                </div>
                <ul class="dash-navbar">
                    {!! getMenu() !!}
                    @stack('custom_side_menu')
                </ul>
              </div>
            </div>
          </div>
        @else
          <div class="navbar-content">
              <ul class="dash-navbar">
                  {!! getMenu() !!}
                  @stack('custom_side_menu')
              </ul>
          </div>
        @endif

    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Función auxiliar para obtener el primer nodo de texto no vacío
    function getFirstTextNode(el) {
        for (let i = 0; i < el.childNodes.length; i++) {
            if (el.childNodes[i].nodeType === Node.TEXT_NODE && el.childNodes[i].textContent.trim() !== "") {
                return el.childNodes[i];
            }
        }
        return null;
    }

    // 1. Procesa elementos con clase .dash-mtext (normalmente en elementos principales)
    let menuTexts = document.querySelectorAll(".dash-mtext");
    menuTexts.forEach(item => {
        let originalText = item.textContent.trim();
        let lowerText = originalText.toLowerCase();

        // Reemplaza "dairy cattle" o "animals" por "Caballos"
        if (lowerText === "dairy cattle" || lowerText === "animals") {
            item.textContent = "Caballos";
            lowerText = "caballos"; // Actualiza para los chequeos posteriores

            // Cambia el ícono asociado
            let iconContainer = item.closest(".dash-link").querySelector(".dash-micon i");
            if (iconContainer) {
                iconContainer.className = "";
                iconContainer.innerHTML = '<img src="https://erp.alfabusiness.app/uploads/users-avatar/Logo_AF_RGB-02_1739121626.png" style="width: 100%; height: 100%;">';
            }
        }

        // Si el texto contiene "piensos", cámbialo a "Gestión de Alimentos"
        if (lowerText.includes("piensos")) {
            item.textContent = "Gestión de Alimentos";
            lowerText = "gestión de alimentos";
        }

        // Si el texto contiene palabras prohibidas, elimina el elemento completo
        if (lowerText.includes("leche") || lowerText.includes("milk") || lowerText.includes("breeding") || lowerText.includes("lácteo")) {
            let li = item.closest(".dash-item");
            if (li) li.remove();
        }
    });

    // 2. Procesa todos los enlaces .dash-link (para detectar aquellos que NO tienen .dash-mtext)
    document.querySelectorAll(".dash-link").forEach(link => {
        // Si existe un nodo de texto directo, lo usamos
        let textNode = getFirstTextNode(link);
        if (textNode) {
            let originalText = textNode.textContent.trim();
            let lowerText = originalText.toLowerCase();

            // Si el texto contiene "piensos", se reemplaza por "Gestión de Alimentos"
            if (lowerText.includes("piensos")) {
                textNode.textContent = "Gestión de Alimentos";
            }
            // Y si contiene palabras prohibidas, se elimina el elemento completo
            if (lowerText.includes("leche") || lowerText.includes("milk") || lowerText.includes("breeding") || lowerText.includes("lácteo")) {
                let li = link.closest(".dash-item");
                if (li) li.remove();
            }
        }
    });

    // 3. Limpia los submenús vacíos (ul.dash-submenu sin elementos li)
    document.querySelectorAll(".dash-submenu").forEach(submenu => {
        if (!submenu.querySelector("li")) {
            submenu.remove();
        }
    });
});
</script>