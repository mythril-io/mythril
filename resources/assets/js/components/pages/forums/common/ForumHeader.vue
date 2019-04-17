<template>

  <section class="hero is-primary">
    <div class="hero-bg-forums">
      <div class="hero-body">
        <div class="container">
          <h1 class="title has-text-weight-light">
            <span class="has-background-primary" v-html="quote.caption"></span>
          </h1>
          <h2 class="subtitle is-6 has-text-dark">
             <span class="has-background-primary	">-{{ quote.character }}</span>
          </h2>
        </div>
      </div>
    </div>
  </section>

</template>

<script>
export default {
  data() {
    return {
      quotesArray: [
        { 
          'caption': '"Stay awhile and listen!"', 
          'character': 'Decard Cain (Diablo)' 
        },
        { 
          'caption': '"You\'ve met with a terrible fate, haven\'t you?"', 
          'character': 'Happy Mask Salesman (The Legend of Zelda: Majora\'s Mask)' 
        },
        { 
          'caption': '"It\'s Dangerous to go Alone! Take This"', 
          'character': 'Elderly Man (The Legend of Zelda)' 
        },
        { 
          'caption': '"I am the leading man. Might need to do something heroic."', 
          'character': 'Balthier (Final Fantasy XII)' 
        },
        { 
          'caption': '"Reinforcements? I <i>am</i> the reinforcements."', 
          'character': 'Ashley Riot (Vagrant Story)' 
        },        { 
          'caption': '"Would you kindly?"', 
          'character': 'Andrew Ryan (Bioshock)' 
        },
        
      ],
      quote: []     
    };
  },
  methods: {
    getFilteredTags(text) {
        this.tags = data.filter((option) => {
            return option.name
                .toString()
                .toLowerCase()
                .indexOf(text.toLowerCase()) >= 0
        })
    } 
  },
  created() {
    this.quote = this.quotesArray[Math.floor(Math.random() * (this.quotesArray.length -1))];
  }
}
</script>
