import api from '../config/api'

export default {
  fetchArticles () {
    return api.get('article')
  },
  sendCreateRequest (article) {
    return api.post('article', article)
  },
  sendUpdateRequest (article) {
    return api.put('article/' + article.id, article)
  },
  sendDeleteRequest (articleId) {
    return api.remove('article/' + articleId)
  }
}
