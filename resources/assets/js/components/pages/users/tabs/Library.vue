<template>
<div>

<!--   <div class="content">
    <h2>Library</h2>
  </div> -->

  <div class="tabs is-centered" v-if="displayedUser.libraries.length > 0">
    <ul>
      <router-link tag="li" :class="{ 'is-active': allSections }" :to="{name: 'UserLibrarySection', params: { status: 'all' }}">
        <a>All</a>
      </router-link>
      <router-link tag="li" :class="{ 'is-active': playingSection && !allSections}" :to="{name: 'UserLibrarySection', params: { status: 'playing' }}" v-if="currentlyPlaying.length > 0">
        <a>Currently Playing</a>
      </router-link>
      <router-link tag="li" :class="{ 'is-active': continuousSection && !allSections}" :to="{name: 'UserLibrarySection', params: { status: 'continuous' }}" v-if="continuous.length > 0">
        <a>Continuously Playing</a>
      </router-link>
      <router-link tag="li" :class="{ 'is-active': completedSection && !allSections}" :to="{name: 'UserLibrarySection', params: { status: 'completed' }}" v-if="completed.length > 0">
        <a>Completed</a>
      </router-link>
      <router-link tag="li" :class="{ 'is-active': planToPlaySection && !allSections}" :to="{name: 'UserLibrarySection', params: { status: 'plan-to-play' }}" v-if="planToPlay.length > 0">
        <a>Plan To Play</a>
      </router-link>
      <router-link tag="li" :class="{ 'is-active': onHoldSection && !allSections}" :to="{name: 'UserLibrarySection', params: { status: 'on-hold' }}" v-if="onHold.length > 0">
        <a>On Hold</a>
      </router-link>
      <router-link tag="li" :class="{ 'is-active': droppedSection && !allSections}" :to="{name: 'UserLibrarySection', params: { status: 'dropped' }}" v-if="dropped.length > 0">
        <a>Dropped</a>
      </router-link>
    </ul>
  </div>

  <div class="content" v-if="displayedUser.libraries.length == 0">
    <h2>Library</h2>

    <div class="notification is-warning" >
      This user has no games in their library.
    </div>
  </div>

  <div class="content" v-if="currentlyPlaying.length > 0 && playingSection">
    <h2>Currently Playing</h2>
      <table class="table is-hoverable is-fullwidth is-striped">
        <thead>
          <tr>
            <th>Title</th>
            <th>Platform</th>
            <th><abbr title="Out of 10">Score</abbr></th>
            <th>Own</th>
            <th>Digital</th>
            <th>Hours Played</th>
            <th v-if="userOwns">Notes</th>
            <!-- <th v-if="userOwns"></th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in currentlyPlaying">
            <th>
              <router-link :to="{name: 'Game', params: { id: entry.game.id }}">{{ getGameTitle(entry) }}</router-link>
            </th>
            <td>
              {{ entry.release.platform.name }}
              <span class="icon has-text-info tooltip" :data-tooltip="'Publisher: ' + entry.release.publisher.name">
                <i class="fas fa-info-circle"></i>
              </span>
            </td>
            <td>{{ entry.score ? entry.score : 'N/A' }}</td>
            <td>{{ entry.own ? 'Yes' : 'No' }}</td>
            <td>{{ entry.digital ? 'Yes' : 'No' }}</td>
            <td>{{ entry.hours ? entry.hours : 'N/A' }}</td>
            <td v-if="userOwns">
              <span v-if="entry.notes" class="tooltip is-tooltip-multiline is-tooltip-left" :data-tooltip="entry.notes"><abbr title="">View</abbr></span>
              <span v-else>N/A</span>
            </td>
<!--             <td v-if="userOwns">
              <div class="buttons">
                <span class="button is-warning is-small" @click="startEditMode(entry)" title="Edit">
                  <span class="icon is-small">
                    <i class="fas fa-edit"></i>
                  </span>
                </span>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
  </div>

  <div class="content" v-if="continuous.length > 0 && continuousSection">
    <h2>Continuously Playing</h2>
      <table class="table is-hoverable is-fullwidth is-striped">
        <thead>
          <tr>
            <th>Title</th>
            <th>Platform</th>
            <th><abbr title="Out of 10">Score</abbr></th>
            <th>Own</th>
            <th>Digital</th>
            <th>Hours Played</th>
            <th v-if="userOwns">Notes</th>
            <!-- <th v-if="userOwns"></th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in continuous">
            <th>
              <router-link :to="{name: 'Game', params: { id: entry.game.id }}">{{ getGameTitle(entry) }}</router-link>
            </th>
            <td>
              {{ entry.release.platform.name }}
              <span class="icon has-text-info tooltip" :data-tooltip="'Publisher: ' + entry.release.publisher.name">
                <i class="fas fa-info-circle"></i>
              </span>
            </td>
            <td>{{ entry.score ? entry.score : 'N/A' }}</td>
            <td>{{ entry.own ? 'Yes' : 'No' }}</td>
            <td>{{ entry.digital ? 'Yes' : 'No' }}</td>
            <td>{{ entry.hours ? entry.hours : 'N/A' }}</td>
            <td v-if="userOwns">
              <span v-if="entry.notes" class="tooltip is-tooltip-multiline is-tooltip-left" :data-tooltip="entry.notes"><abbr title="">View</abbr></span>
              <span v-else>N/A</span>
            </td>
<!--             <td v-if="userOwns">
              <div class="buttons">
                <span class="button is-warning is-small" @click="startEditMode(entry)" title="Edit">
                  <span class="icon is-small">
                    <i class="fas fa-edit"></i>
                  </span>
                </span>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
  </div>

  <div class="content" v-if="completed.length > 0 && completedSection">
      <h2>Completed</h2>

      <table class="table is-hoverable is-fullwidth is-striped">
        <thead>
          <tr>
            <th>Game</th>
            <th>Platform</th>
            <th><abbr title="Out of 10">Score</abbr></th>
            <th>Own</th>
            <th>Digital</th>
            <th>Hours Played</th>
            <th v-if="userOwns">Notes</th>
            <!-- <th v-if="userOwns"></th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in completed">
            <th>
              <router-link :to="{name: 'Game', params: { id: entry.game.id }}">{{ getGameTitle(entry) }}</router-link>
            </th>
            <td>
              {{ entry.release.platform.name }}
              <span class="icon has-text-info tooltip" :data-tooltip="'Publisher: ' + entry.release.publisher.name">
                <i class="fas fa-info-circle"></i>
              </span>
            </td>
            <td>{{ entry.score ? entry.score : 'N/A' }}</td>
            <td>{{ entry.own ? 'Yes' : 'No' }}</td>
            <td>{{ entry.digital ? 'Yes' : 'No' }}</td>
            <td>{{ entry.hours ? entry.hours : 'N/A' }}</td>
            <td v-if="userOwns">
              <span v-if="entry.notes" class="tooltip is-tooltip-multiline is-tooltip-left" :data-tooltip="entry.notes"><abbr title="">View</abbr></span>
              <span v-else>N/A</span>
            </td>
<!--             <td v-if="userOwns">
              <div class="buttons">
                <span class="button is-warning is-small" @click="startEditMode(entry)" title="Edit">
                  <span class="icon is-small">
                    <i class="fas fa-edit"></i>
                  </span>
                </span>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
  </div>

  <div class="content" v-if="planToPlay.length > 0 && planToPlaySection">
      <h2>Plan To Play</h2>

      <table class="table is-hoverable is-fullwidth is-striped">
        <thead>
          <tr>
            <th>Game</th>
            <th>Platform</th>
            <th><abbr title="Out of 10">Score</abbr></th>
            <th>Own</th>
            <th>Digital</th>
            <th>Hours Played</th>
            <th v-if="userOwns">Notes</th>
            <!-- <th v-if="userOwns"></th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in planToPlay">
            <th>
              <router-link :to="{name: 'Game', params: { id: entry.game.id }}">{{ getGameTitle(entry) }}</router-link>
            </th>
            <td>
              {{ entry.release.platform.name }}
              <span class="icon has-text-info tooltip" :data-tooltip="'Publisher: ' + entry.release.publisher.name">
                <i class="fas fa-info-circle"></i>
              </span>
            </td>
            <td>{{ entry.score ? entry.score : 'N/A' }}</td>
            <td>{{ entry.own ? 'Yes' : 'No' }}</td>
            <td>{{ entry.digital ? 'Yes' : 'No' }}</td>
            <td>{{ entry.hours ? entry.hours : 'N/A' }}</td>
            <td v-if="userOwns">
              <span v-if="entry.notes" class="tooltip is-tooltip-multiline is-tooltip-left" :data-tooltip="entry.notes"><abbr title="">View</abbr></span>
              <span v-else>N/A</span>
            </td>
<!--             <td v-if="userOwns">
              <div class="buttons">
                <span class="button is-warning is-small" @click="startEditMode(entry)" title="Edit">
                  <span class="icon is-small">
                    <i class="fas fa-edit"></i>
                  </span>
                </span>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
  </div>

  <div class="content" v-if="onHold.length > 0 && onHoldSection">
      <h2>On Hold</h2>

      <table class="table is-hoverable is-fullwidth is-striped">
        <thead>
          <tr>
            <th>Game</th>
            <th>Platform</th>
            <th><abbr title="Out of 10">Score</abbr></th>
            <th>Own</th>
            <th>Digital</th>
            <th>Hours Played</th>
            <th v-if="userOwns">Notes</th>
            <!-- <th v-if="userOwns"></th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in onHold">
            <th>
              <router-link :to="{name: 'Game', params: { id: entry.game.id }}">{{ getGameTitle(entry) }}</router-link>
            </th>
            <td>
              {{ entry.release.platform.name }}
              <span class="icon has-text-info tooltip" :data-tooltip="'Publisher: ' + entry.release.publisher.name">
                <i class="fas fa-info-circle"></i>
              </span>
            </td>
            <td>{{ entry.score ? entry.score : 'N/A' }}</td>
            <td>{{ entry.own ? 'Yes' : 'No' }}</td>
            <td>{{ entry.digital ? 'Yes' : 'No' }}</td>
            <td>{{ entry.hours ? entry.hours : 'N/A' }}</td>
            <td v-if="userOwns">
              <span v-if="entry.notes" class="tooltip is-tooltip-multiline is-tooltip-left" :data-tooltip="entry.notes"><abbr title="">View</abbr></span>
              <span v-else>N/A</span>
            </td>
<!--             <td v-if="userOwns">
              <div class="buttons">
                <span class="button is-warning is-small" @click="startEditMode(entry)" title="Edit">
                  <span class="icon is-small">
                    <i class="fas fa-edit"></i>
                  </span>
                </span>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
  </div>

  <div class="content" v-if="dropped.length > 0 && droppedSection">
      <h2>Dropped</h2>

      <table class="table is-hoverable is-fullwidth is-striped">
        <thead>
          <tr>
            <th>Game</th>
            <th>Platform</th>
            <th><abbr title="Out of 10">Score</abbr></th>
            <th>Own</th>
            <th>Digital</th>
            <th>Hours Played</th>
            <th v-if="userOwns">Notes</th>
            <!-- <th v-if="userOwns"></th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in dropped">
            <th>
              <router-link :to="{name: 'Game', params: { id: entry.game.id }}">{{ getGameTitle(entry) }}</router-link>
            </th>
            <td>
              {{ entry.release.platform.name }}
              <span class="icon has-text-info tooltip" :data-tooltip="'Publisher: ' + entry.release.publisher.name">
                <i class="fas fa-info-circle"></i>
              </span>
            </td>
            <td>{{ entry.score ? entry.score : 'N/A' }}</td>
            <td>{{ entry.own ? 'Yes' : 'No' }}</td>
            <td>{{ entry.digital ? 'Yes' : 'No' }}</td>
            <td>{{ entry.hours ? entry.hours : 'N/A' }}</td>
            <td v-if="userOwns">
              <span v-if="entry.notes" class="tooltip is-tooltip-multiline is-tooltip-left" :data-tooltip="entry.notes"><abbr title="">View</abbr></span>
              <span v-else>N/A</span>
            </td>
<!--             <td v-if="userOwns">
              <div class="buttons">
                <span class="button is-warning is-small" @click="startEditMode(entry)" title="Edit">
                  <span class="icon is-small">
                    <i class="fas fa-edit"></i>
                  </span>
                </span>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
  </div>

</div>
</template>

<script>
export default {
	props: ['displayedUser', 'userOwns'],
  data() {
    return {
      allSections: true,
      playingSection: true, 
      planToPlaySection: true, 
      completedSection: true, 
      onHoldSection: true, 
      droppedSection: true, 
      continuousSection: true, 
    }
  },
  computed: {
    currentlyPlaying() {
      var filtered = _.filter(this.displayedUser.libraries, ['play_status_id', 1])
      return _.orderBy(filtered, 'game.title', 'asc');
    },
    planToPlay() {
      var filtered = _.filter(this.displayedUser.libraries, ['play_status_id', 2])
      return _.orderBy(filtered, 'game.title', 'asc');
    },
    completed() {
      var filtered = _.filter(this.displayedUser.libraries, ['play_status_id', 3])
      return _.orderBy(filtered, 'game.title', 'asc');
    },
    onHold() {
      var filtered = _.filter(this.displayedUser.libraries, ['play_status_id', 4])
      return _.orderBy(filtered, 'game.title', 'asc');
    },
    dropped() {
      var filtered = _.filter(this.displayedUser.libraries, ['play_status_id', 5])
      return _.orderBy(filtered, 'game.title', 'asc');
    },
    continuous() {
      var filtered = _.filter(this.displayedUser.libraries, ['play_status_id', 6])
      return _.orderBy(filtered, 'game.title', 'asc');
    }
  },
  methods: {
    getGameTitle(entry) {
      return entry.release.alternate_title ? entry.release.alternate_title : entry.game.title
    },
    changeTab(status) {
        this.toggleSections(false)
        if(status == 'playing') { this.playingSection = true }
        else if(status == 'plan-to-play') { this.planToPlaySection = true }
        else if(status == 'completed') { this.completedSection = true }
        else if(status == 'on-hold') { this.onHoldSection = true }
        else if(status == 'dropped') { this.droppedSection = true }
        else if(status == 'continuous') { this.continuousSection = true }
        else { this.toggleSections(true) }
    },
    toggleSections(value) {
      this.allSections = value
      this.playingSection = value 
      this.planToPlaySection = value 
      this.completedSection = value 
      this.onHoldSection = value 
      this.droppedSection = value 
      this.continuousSection = value 
    }
  },
  created() {
    var status = this.$route.params.status
    this.changeTab(status)
  }
}
</script>