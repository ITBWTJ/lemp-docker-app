<template>
    <nav aria-label="...">
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: !hasPrev }">
                <a class="page-link" href="#" @click="changePage(prevPage)" >Previous</a>
            </li>

            <li class="page-item" v-if="paginStart !== 1">
                <a class="page-link" href="#" >...</a>
            </li>

            <li class="page-item" :class="{ active: page === current }" @click="changePage(page)"  v-for="page in pageRange">
                <a class="page-link" href="#">{{ page }}</a>
            </li>

            <li class="page-item" v-if="paginEnd !== totalPage">
                <a class="page-link" href="#" >...</a>
            </li>

            <li class="page-item" :class="{ disabled: !hasNext }">
                <a class="page-link" @click="changePage(nextPage)" href="#">Next</a>
            </li>
        </ul>
    </nav>
</template>

<script>

    export default {
        name: "Pagination",
        props: {
            current: {
                type: Number,
                default: 1
            },
            perPage: {
                type: Number,
                default: 4
            },
            total: {
                type: Number,
                default: 0
            },
        },
        computed: {
            nextPage: function () {
                return this.current + 1;
            },
            prevPage: function () {
                return this.current - 1;
            },

            hasPrev: function () {
                return this.current > 1;
            },
            hasNext: function () {
                return this.current < this.totalPage;
            },
            paginStart: function () {
                return (this.current - 2 > 0) ? this.current - 2 : 1;
            },
            paginEnd: function () {
                return (this.current + 2 < this.totalPage) ? this.current + 2 : this.totalPage;
            },
            pageRange: function () {
                let range = [];

                for (let i = this.paginStart; i <= this.paginEnd; i++) {
                    range.push(i);
                }

                return range;
            }
        },
        methods: {
            changePage: function (page) {
                this.$emit('page-changed', page);
            }
        }
    }
</script>

<style scoped>
    .pagination {
        justify-content: center;
        margin: 30px 0;
    }
</style>