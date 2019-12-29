import $ from 'jquery';
class Search {
    // 1. Describe and create/initiate our object
    constructor() {
        this.resultsDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.events();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue;
    }

    // 2. Events
    events() {
        // bind(this) para que haga referencia al objeto del constructor de la clase y no el evento click
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    // 3. Methods (function, actions...)
    typingLogic() {
        if ( this.searchField.val() != this.previousValue ) {
            clearTimeout(this.typingTimer);
            if ( this.searchField.val() ) {
                if ( !this.isSpinnerVisible ) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 2000); // Primer parámetro es la función a ejecutar al pasar los milisegundos del segundo parámetro
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResults() {
        //$.getJSON(url, callback); // ES6 Arrow function () => {}
        // ES6 Template file `` `<p>${codido js... no html}</p>`
        // Ternary operator condition ? true : false
        $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), posts =>  {
            this.resultsDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${posts.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                    ${posts.map(items => `<li><a href="${items.link}">${items.title.rendered}</a></li>`).join('')}
                ${posts.length ? '</ul>' : ''}
            `);
            this.isSpinnerVisible = false;
        });
    }

    keyPressDispatcher(e) {
        //console.log(e.keyCode);
        if ( e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
            this.openOverlay()
        }

        if ( e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay()
        }
    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        console.log("Our open method just run!");
        this.isOverlayOpen = true;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        console.log("Our colse method just run!");
        this.isOverlayOpen = false;
    }
}

// Exportamos ésta línea de código al main script file
export default Search;