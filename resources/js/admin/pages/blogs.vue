<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Blogs <Button @click="$router.push('/createBlog')"><Icon type="md-add" />Create Blogs</Button></p>
                    
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Title</th>
								<th>Categories</th>
								<th>Tags</th>
								<th>Views</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
								<td>{{blog.id}}</td>
								<td class="_table_name">{{blog.title}}</td>
								<td> <span  v-for="(c, j) in blog.cat" v-if="blog.cat.length"><Tag type="border">{{c.categoryName}}</Tag></span> </td>
								<td> <span v-for="(t, j) in blog.tag" v-if="blog.tag.length"><Tag type="border">{{t.tagName}}</Tag></span> </td>
								<td> {{blog.views}}</td>
								<td>
                                    <Button type="info" size="small" >Visit blog</Button>
                                    <Button type="info" size="small" @click="$router.push(`/editblog/${blog.id}`)" v-if="isUpdatePrimtted">Edit</Button>
                                    <Button type="error" size="small" @click="showDeletingModal(blog,i)" :loading="blog.isDeleting" v-if="isDeletePrimtted">Delete</Button>
								</td>
							</tr>
								 <Page :total="pageInfo.total" :current="pageInfo.current_page" :page-size="parseInt(pageInfo.per_page)" @on-change="getBlogData" v-if="pageInfo"/>
						</table>
					</div>
				</div>
                    
                    <!-- delete alert modal -->
                    <Modal v-model="showDeleteModal" width="360">
                        <p slot="header" style="color:#f60;text-align:center">
                            <Icon type="ios-information-circle"></Icon>
                            <span>Delete confirmation</span>
                        </p>
                        <div style="text-align:center">
                            <p>Are you sure you want to delete tag?.</p>
                        </div>
                        <div slot="footer">
                            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteTag">Delete</Button>
                        </div>
                    </Modal>
			</div>
		</div>
	</div>
</template>

<script>
export default {
    data(){
        return {
            isAdding : false,
			tags : [],
            index : -1,
			showDeleteModal: false,
			isDeleing : false,
			deleteItem: {},
            deletingIndex: -1,
            blogs: [],
            total: 1,
            pageInfo : null,
        }
    },
    methods : {
        async deleteTag(){
            this.isDeleting = true
            const res = await this.callApi('post','app/delete_blog',this.deleteItem)
            if(res.status === 200){
                this.blogs.splice(this.deletingIndex,1)
                this.success('Blog has been deleted successfully')
            }else{
                this.swr()
            }
            this.isDeleting = false,
            this.showDeleteModal = false
        },
        showDeletingModal(blog,i){
            // const deleteModalObj = {
            //     showDeleteModal : true,
            //     deleteUrl: 'app/delete_blog',
            //     data: {id: blog.id},
            //     deletingIndex : i,
            //     isDeleted : false,
            //     msg : 'Are you sure you want to delete this blog?',
            //     successMsg : 'Blog has been deleted successfully'
            // }
            this.deleteItem = blog
            this.deletingIndex=i
            this.showDeleteModal = true
        },
        async getBlogData(page=1){
             const res= await this.callApi('get',`app/blogsdata?page=${page}&total=${this.total}`)
            if(res.status == 200){
                this.blogs=res.data.data
                this.pageInfo = res.data
            }else{
                this.swr()
            }
        }

    },
    async created(){
        this.getBlogData()
            // const res= await this.callApi('get','app/blogsdata')
            // if(res.status == 200){
            //     this.blogs=res.data
            // }else{
            //     this.swr()
            // }
        },
    }
</script>