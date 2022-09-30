<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_one">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">{{this.blogsCount}}</p>

								<p class="_1adminOverveiw_card_left_title">Blogs</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="ios-paper" />
							</div>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">{{this.usersCount}}</p>

								<p class="_1adminOverveiw_card_left_title">Users</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="ios-paper-outline" />
							</div>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">{{this.categoriesCount}}</p>

								<p class="_1adminOverveiw_card_left_title">Categories</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="md-copy" />
							</div>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">{{this.tagsCount}}</p>

								<p class="_1adminOverveiw_card_left_title">Tags</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="md-list-box" />
							</div>
						</div>
					</div>
				</div>	
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Recent Blogs</p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>Date</th>
								<th>Title</th>
								<th>Category</th>
								<th>Tag</th>
								<th>Views</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
								<td>{{blog.created_at}}</td>
								<td class="_table_name">{{blog.title}}</td>
								<td> <span  v-for="(c, j) in blog.cat" v-if="blog.cat.length"><Tag type="border">{{c.categoryName}}</Tag></span> </td>
								<td> <span v-for="(t, j) in blog.tag" v-if="blog.tag.length"><Tag type="border">{{t.tagName}}</Tag></span> </td>
								<td> {{blog.views}}</td>
								<td>
                                    <Button type="info" size="small" >Visit blog</Button>
								</td>
							</tr>	
							
								<!-- ITEMS -->


						</table>
					</div>
				</div>
				 <Page :total="100" />

			</div>
		</div>
	</div>
</template>

<script>
export default {
	    data(){
        return {
			blogsCount: 0,
			usersCount: 0,
			tagsCount: 0 ,
			categoriesCount:0,
			blogs : [],
        }
    },
	async created(){
		const [count,blogs] = await Promise.all([
			this.callApi('get','app/blogCount'),
			this.callApi('get','app/blogsdata')
		])
		if(count.status==200 && blogs.status==200){
			this.blogs = blogs.data
			this.blogsCount = count.data['0']
			this.usersCount = count.data['1']
			this.tagsCount = count.data['2']
			this.categoriesCount = count.data['3']
		}else{
			this.swr()
		}
	}
}
</script>