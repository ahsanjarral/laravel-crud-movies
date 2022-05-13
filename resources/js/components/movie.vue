<template>
	<div class="container p-5">

		<button type="button" class="btn btn-link mb-4 float-right" data-toggle="modal" data-target="#myModal" @click="formReset"><span class="glyphicon glyphicon-plus" area-hidden="true"></span> Add Movie</button>

		<div class="modal" id="myModal">
			<div class="modal-dialog">
				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title text-muted">Add New Movie</h5>
						<button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">

						<div class="text-sm text-danger" if="errors != ''">
							<p v-for="error in errors" class="mb-0" :key="error"><small>{{ error }} </small></p>
						</div>

						<div class="form-group">
							<label>Title</label>
							<input type="text" class="form-control" v-model="form.title">
						</div>
						<div class="form-group">
							<label>Category</label>
							<input type="text" class="form-control" v-model="form.category">
						</div>
						<div class="form-group">
							<label>Release Date</label>
							<input type="date" format="dd/mm/yyyy" class="form-control" v-model="form.release_date">
						</div>
						<div class="form-group">
							<label>Desciption</label>
							<input type="text" class="form-control" v-model="form.desc">
						</div>
						<div class="form-group">
							<label>Rating</label>
							<input type="text" class="form-control" v-model="form.rating">
						</div>

						<button v-if="id == ''" type="button" class="btn btn-primary" @click="addMovie">Submit</button>

						<button v-else type="button" class="btn btn-primary" @click="updateMovie">Update</button>
						
					</div>
				</div>
			</div>
		</div>

		<table class="table">
			<thead>
				<tr>
				<!-- <th scope="col">#</th> -->
				<th scope="col">Title</th>
				<th scope="col">Category</th>
				<th scope="col">Release Date</th>
				<th scope="col">Description</th>
				<th scope="col">Rating</th>
				<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<template v-for="movie in movies" :key="movie.id">
					<tr scope="row">
						<td>{{ movie.title }}</td>
						<td>{{ movie.category }}</td>
						<td>{{ movie.release_date }}</td>
						<td>{{ movie.desc }}</td>
						<td>{{ movie.rating }}</td>
						<td>
							<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" @click="editMovie(movie)"><span class="glyphicon glyphicon-pencil" area-hidden="true"></span></button>
							<button type="button" class="btn btn-sm btn-danger ml-2" @click="deleteMovie(movie.id)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
						</td>
					</tr>					
				</template>
			</tbody>
		</table>
	</div>
</template>
<script>
	import { ref,reactive, onMounted } from 'vue';
	import axios from 'axios';
	export default{
		setup(){
			const movies  = ref([]);
			const errors  = ref([]);
			const id  = ref('');
			const page  = 1;
			const form = reactive({
				title:'',
				category:'',
				release_date:'',
				desc:'',
				rating:''
			});

			//get all movies to list in the table
			const getMovie = async() =>{
				let res = await axios.get('api/movies');
				movies.value = res.data.data;
			}

			//add new movie to database.
			const addMovie = async() => {
                try{
					await axios.post('api/movies',form);
					// console.log($test);
					getMovie();
					formReset();
					$("#myModal").modal('hide');
				}catch(e){
					if(e.response.status == 422){
						var data = [];
						for(const key in e.response.data.errors){
							data.push(e.response.data.errors[key][0]);
						}
						errors.value = data;
					}
				}
			}
			
			//api call function to update the selected movie.
			const updateMovie = async() => {
				try{
					await axios.patch('api/movies/' + id.value,form);
					getMovie();
					formReset();
					$("#myModal").modal('hide');
				}catch(e){
					if(e.response.status == 422){
						var data = [];
						for(const key in e.response.data.errors){
							data.push(e.response.data.errors[key][0]);
						}
						errors.value = data;
					}
				}
			}

			//set up edit movie selection 
			const editMovie = (movie)=>{
                id.value = movie.id;
				form.title = movie.title;
				form.category = movie.category;
				form.release_date = movie.release_date;
				form.desc = movie.desc;
				form.rating = movie.rating;
			}

			onMounted(getMovie());

			//function to reset the form 
			const formReset = () =>{
                id.value = '';
				form.title = '';
				form.category = '';
				form.release_date = '';
				form.desc = '';
				form.rating = '';
			}

			//delete movie using api call.
            const deleteMovie = async(id)=>{
                await axios.delete('api/movies/'+ id);
                getMovie();
            }

			return {
				errors,
				movies,
				id,
				form,
				addMovie,
				editMovie,
				updateMovie,
				formReset,
				deleteMovie
			}
		}
	}
</script>