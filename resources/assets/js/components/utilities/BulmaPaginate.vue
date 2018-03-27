<template>
  
<div class="columns is-centered">
  <div class="column is-narrow">
    <nav class="pagination" role="navigation" aria-label="pagination">
      <paginate
        :page-count="pages"
        :initial-page="initialPage"
        :click-handler="clickCallback"
        :page-range="2"
        :prev-text="'Prev'"
        :next-text="'Next'"
        :page-link-class="'pagination-link'"
        :active-class="'is-current'"
        :prev-link-class="'pagination-previous'"
        :next-link-class="'pagination-next'"
        :no-li-surround="true">
      </paginate>
    </nav>
  </div>
</div>

</template>

<script>
export default {
 	props: ['pages', 'initialPage'],
	methods: {
		clickCallback(pageNum) { this.$emit('paginate', pageNum) }
	}
}
</script>
     
<style>
  .pagination-previous.disabled, .pagination-next.disabled {
      background-color: #dbdbdb;
      border-color: #dbdbdb;
      -webkit-box-shadow: none;
      box-shadow: none;
      color: #7a7a7a;
      opacity: 0.5;
      pointer-events: none;
  }
  .disabled {
    border: 0px !important;
    color: #b5b5b5;
    pointer-events: none;
  }
</style>