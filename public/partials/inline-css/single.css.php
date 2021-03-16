<style>
.buw_single {
    display: flex;
    flex-wrap: wrap;
}

.buw_single .title {
    flex-basis: 100%;
}

.buw_single .photos {
    flex-basis: 100%;
    margin-top: 1em;
    margin-bottom: 2em;
}

.buw_single iframe {
    flex-basis: 500px;
    flex-grow: 1;
    flex-shrink: 0;
    border: 0;
    outline: 0;
}

.buw_single .info {
    flex-basis: 50%;
    flex-grow: 1;
    flex-shrink: 0;
}

.buw_single .title {
    margin: .5em 0 .25em 0;
}

.buw_single .price {
    margin: 1em 0 1em auto;
}

.product_photos {
    margin-bottom: 2rem;
}

.gallery-cell {
    width: 100%;
}

.gallery-cell img {
    width: 100%;
    height: auto;
}

.buw_single a.back {
    display: block;
    margin-top: 1em;
}

.buw_single.wide .title {
    order: 1;
}
.buw_single.wide .intro {
    flex-basis: 50%;
    order: 3;
}
.buw_single.wide iframe {
    flex-basis: 50%;
    order: 4;
}
.buw_single.wide .photos {
    order: 2;
}
.buw_single.wide .info {
    order: 5;
}
</style>