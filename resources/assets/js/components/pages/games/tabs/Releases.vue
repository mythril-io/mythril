<template>
	<div>
		<div class="content">
			<h2>Releases</h2>
		</div>

		<b-field grouped group-multiline>
				<div class="control is-flex">
						<b-switch v-model="isPaginated">Paginated</b-switch>
				</div>
				<div class="control is-flex">
						<b-switch v-model="isStriped">Striped</b-switch>
				</div>
				<div class="control is-flex">
						<b-switch v-model="isNarrowed">Narrowed</b-switch>
				</div>
				<div class="control is-flex">
						<b-switch v-model="isHoverable">Hoverable</b-switch>
				</div>

				<b-select v-model="perPage" :disabled="!isPaginated">
						<option value="5">5 per page</option>
						<option value="10">10 per page</option>
						<option value="15">15 per page</option>
						<option value="20">20 per page</option>
				</b-select>
		</b-field>

		<b-table
			:data="sortedReleases"
			:striped="isStriped"
			:narrowed="isNarrowed"
			:hoverable="isHoverable"
			:loading="false"
			:mobile-cards="true"
			:paginated="isPaginated"
			:per-page="perPage"
			:default-sort-direction="defaultSortDirection"
      default-sort="date">

			<template slot-scope="props">
					<b-table-column field="platform.name" label="Platform" centered sortable >
							<span class="tag is-info">
								{{ props.row.platform.name }}
							</span>
					</b-table-column>

					<b-table-column field="alternate_title" label="Alternate Title" sortable>
							{{ props.row.alternate_title ? props.row.alternate_title : "-" }}
					</b-table-column>

					<b-table-column field="publisher.name" label="Publisher" sortable>
							{{ props.row.publisher.name}}
					</b-table-column>

					<b-table-column field="codeveloper.name" label="Co-Developer" sortable>
							{{ props.row.codeveloper ? props.row.codeveloper.name : "-" }}
					</b-table-column>

					<b-table-column field="region.name" label="Region" sortable>
							{{ props.row.region ? props.row.region.name : "-" }}
					</b-table-column>

					<b-table-column field="date" label="Date" sortable>
							{{ props.row.date | dateFormat }}
					</b-table-column>
			</template>

			<template slot="empty">
					<section class="section">
							<div class="content has-text-grey has-text-centered">
									<p>
											<b-icon
													icon="emoticon-sad"
													size="is-large">
											</b-icon>
									</p>
									<p>Nothing here.</p>
							</div>
					</section>
			</template>
		</b-table>

	</div>
</template>

<script>
import moment from 'moment';

export default {
    props: ['game'],
		filters: {
			dateFormat(date) {
				if(date)
				{
					return moment.utc(date).format("MMM Do YYYY");;
				}
				else
				{
					return "-";
				}
			}
		},
		data() {
				return {
						isStriped: true,
						isNarrowed: false,
						isHoverable: false,
						isPaginated: true,
						defaultSortDirection: 'desc',
						perPage: 10
				}
		},
  	computed: {
	    sortedReleases() {
	      return _.orderBy(this.game.releases, 'platform.name', 'asc');
	  	}
    },
		methods: {
      	addToLibrary(release) {

      	},
    }
}
</script>