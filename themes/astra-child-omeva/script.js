function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min)
}

jQuery(document).ready(function($){
    // move home header random
    let cardsDesktop = $(".omeva-hp-header-desktop figure");
    const rndIntDesktop = randomIntFromInterval(0, cardsDesktop.length -1);
    if (rndIntDesktop > 0){
        cardsDesktop.eq(0).before(cardsDesktop.eq(rndIntDesktop));
    }

    let cardsMobile = $(".omeva-hp-header-mobile figure");
    const rndIntMobile = randomIntFromInterval(0, cardsMobile.length -1);
    if (rndIntMobile > 0){
        cardsMobile.eq(0).before(cardsMobile.eq(rndIntMobile));
    }
});
