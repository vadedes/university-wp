import $ from 'jquery';

class Search {
    // 1.Describe and create/initiate object
    constructor() {
        this.addSearchHTML();
        this.resultsDiv = $('#search-overlay__results');
        this.openButton = $('.js-search-trigger');
        this.closeButton = $('.search-overlay__close');
        this.searchOverlay = $('.search-overlay');
        //selector for search input field typing logic
        this.searchField = $('#search-term');
        this.events();
        // condition to ensure that the methods are only ran once even if the button press in held continuously
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.typingTimer;
    }

    // 2 Events
    events() {
        this.openButton.on('click', this.openOverlay.bind(this));
        this.closeButton.on('click', this.closeOverlay.bind(this));
        // open and close search when s or esc keys are pressed
        $(document).on('keydown', this.keyPressDispatcher.bind(this));
        //search input typing event
        this.searchField.on('keyup', this.typingLogic.bind(this));
    }

    // 3 methods
    typingLogic() {
        // condition is meant to make sure that loading state is not affected by irrelevant key press
        // and to allow load state only when the actual input value changes
        if (this.searchField.val() != this.previousValue) {
            //reset any ongoing timers first
            clearTimeout(this.typingTimer);

            //condition to not show the search results and loader spinner if the field input is empty or deleted
            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }
        }
        //compare prev and current value
        this.previousValue = this.searchField.val();
    }

    getResults() {
        //async method
        $.when(
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
        ).then(
            (posts, pages) => {
                var combinedResults = posts[0].concat(pages[0]);
                this.resultsDiv.html(`
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${combinedResults.length ? `<ul class="link-list min-list">` : '<p>No general information matches that search</p>'}
                        ${combinedResults.map((item) => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
                    ${combinedResults.length ? `</ul>` : ''}
                `);
                this.isSpinnerVisible = false;
            },
            () => {
                this.resultsDiv.html('<p>Unexpected error: please try again.</p>');
            }
        );
        // used arrow function to make sure the this keyword still points to our search object and now the getJSON method
        //root_url came from functions.php under university files
        //synchronus method
        // $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), (posts) => {
        //     this.resultsDiv.html(`
        //         <h2 class="search-overlay__section-title">General Information</h2>
        //         ${posts.length ? `<ul class="link-list min-list">` : '<p>No general information matches that search</p>'}
        //             ${posts.map((post) => `<li><a href="${post.link}">${post.title.rendered}</a></li>`).join('')}
        //         ${posts.length ? `</ul>` : ''}
        //     `);
        //     this.isSpinnerVisible = false;
        // });
    }

    keyPressDispatcher(e) {
        //3rd condition check ensures that search modal is not triggered when user is typing on other forms
        //within the website. "s" and "esc" keys trigger the modal to open and close which should only happen when the users are not filling out any form within the site.
        if (e.keyCode == 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
            this.openOverlay();
        }

        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.searchOverlay.addClass('search-overlay--active');
        $('body').addClass('body-no-scroll');
        this.searchField.val('');
        setTimeout(() => this.searchField.focus(), 350);
        this.isOverlayOpen = true;
    }
    closeOverlay() {
        this.searchOverlay.removeClass('search-overlay--active');
        $('body').removeClass('body-no-scroll');
        this.isOverlayOpen = false;
        this.resultsDiv.html(``);
    }

    addSearchHTML() {
        $('body').append(`
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term"
                        autocomplete="off">
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                </div>
        
            </div>
            <div class="container">
                <div id="search-overlay__results">
        
                </div>
            </div>
        </div>
        `);
    }
}

export default Search;
