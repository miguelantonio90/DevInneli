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
  },
  importArticles (dataFile) {
    return api.importData('article/import', dataFile)
  },
  refoundArticle (dataFile) {
    return api.post('article/refound', dataFile)
  },
  fetchArticleNumber () {
    return api.get('article/number/get')
  }
}
