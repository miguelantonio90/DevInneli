import en from 'vuetify/es5/locale/en'

export default {
  label: 'English',
  // page login
  login: 'login',
  forgot: 'Forgot Password?',
  reset_password: 'Reset Password',
  forgot_btn: 'Send Reset Link',
  username: 'Username',
  name: 'Name',
  firstName: 'First Name',
  lastName: 'Last Name',
  email: 'Email',
  password: 'Password',
  confirm: 'Confirm',
  register: 'Register',
  noDefined: 'No defined',
  aboutMe: 'About me',
  activeAccount:
    'Hello, your account has been created, but it has not been activated yet. Please access the registered email, you have been sent a message for activation.',
  confirm_password: 'Confirm Password',
  company: 'Company',
  first_name: 'First Name',
  last_name: 'Last Name',
  address: 'Address',
  city: 'City',
  country: 'Country',
  position: 'Position',
  postal_code: 'Postal Code',
  about_me: 'About Me',
  phone: 'Phone',
  wait: 'Please a wait...',
  phone_holder: 'Enter a phone number',
  // menu
  menu: {
    setting: 'Setting',
    home: 'Home',
    dashboard: 'Dashboard',
    access_denied: 'Access Deny',
    verify: 'Email Verify',
    welcome: 'Welcome',
    profile: 'User Profile',
    logout: 'Logout',
    user_list: 'User List',
    user: 'Users',
    access: 'Access permission',
    access_list: 'Permission List',
    client: 'Client',
    client_list: 'Client List',
    articles: 'Articles',
    category_list: 'Category List',
    product_list: 'Product List',
    modifiers_list: 'Modifiers List',
    discounts_list: 'Discounts List',
    shop: 'Shop',
    resume: 'Resume',
    sell_product: 'Sale by Products',
    sell_category: 'Sale by Categories',
    sell_user: 'Sale by Employees',
    sell_types_payment: 'Sale by types of payment'
  },
  // settings
  settings: {
    title: 'Theme Settings',
    color: 'Color Options',
    lang: 'Language',
    sidebar: 'Sidebar Option'
  },
  rule: {
    required: '{0} is required',
    min: 'Just a minimum of {0} characters is allowed',
    max: 'Just a maximum of {0} characters is allowed',
    length: 'This field need {0} characters',
    match: '{0} and {1} do not match',
    bad_email: '{0}  must be valid',
    required_element: 'This element is required',
    bad_phone: '{0}  must be valid'
  },
  // error
  messages: {
    refused: 'Failed: Connections refused.',
    success_avatar: 'The image was successfully saved.',
    warning_delete: 'You won\'t be able to revert this!',
    success_profile: 'The data has been updated.',
    check_mail:
      'An email has been sent with the details to change password.',
    password_success: 'The password was updated successfully.'
  },
  // profile
  profile: {
    user: 'User',
    edit_profile: 'Edit Profile',
    sub_profile: 'Complete your profile',
    btn_edit: 'Update'
  },
  // options
  actions: {
    actions: 'Actions',
    accept: 'Accept',
    search: 'Search',
    new: 'New',
    edit: 'Edit',
    delete: 'Delete',
    list: 'List',
    save: 'Save',
    cancel: 'Cancel',
    close: 'Close'
  },
  titles: {
    list: '{0} List',
    new: 'New {0}',
    edit: 'Edit {0}',
    show: 'Show {0}',
    delete: 'Delete {0}'
  },

  access: {
    key: 'Key',
    name: 'Name',
    accessPin: 'Access with Pin Code',
    accessEmail: 'Access with Email',
    description: 'Description'
  },
  ...en
}
