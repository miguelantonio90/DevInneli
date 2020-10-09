import es from 'vuetify/es5/locale/es'

export default {
  label: 'Español',
  // page login
  login: 'login',
  reset_password: 'Cambiar contraseña',
  find_password: 'Enviar su contraseña',
  forgot: 'Olvidó su contraseña?',
  have_pin: 'Bloquer Pantalla',
  pinTile: 'Pin Code',
  forgot_btn: 'Enviar link de cambio',
  username: 'Usuario',
  name: 'Nombre',
  email: 'Correo',
  holder_email: 'Dirección de correo de la compañía',
  firstName: 'Nombre',
  lastName: 'Apellidos',
  password: 'Contraseña',
  pinCode: 'Pin',
  confirm: 'Confirmación',
  register: 'Registrar',
  noDefined: 'No definido',
  aboutMe: 'Acerca de mi',
  activeAccount:
        'Hola, su cuenta se ha creada, pero aún no ha sido activada. Por favor, acceda al correo registrado, se le ha enviado un mensaje para la activación.',
  confirm_password: 'Confirmar Contraseña',
  confirm_pinCode: 'Confirmar Pin',
  company: 'Compañía',
  first_name: 'Nombre(s)',
  last_name: 'Apellidos',
  address: 'Dirección',
  city: 'Ciudad',
  country: 'País',
  province: 'Provincia',
  barCode: 'Código de Barras',
  position: 'Cargo',
  postal_code: 'Código Postal',
  about_me: 'Acerca de mi',
  phone: 'Teléfono',
  wait: 'Espere por favor...',
  phone_holder: 'Entre su número de teléfono',
  price: 'Precio',
  color: 'Color',
  // menu
  menu: {
    setting: 'Configuración',
    home: 'Inicio',
    dashboard: 'Dashboard',
    access_denied: 'Acceso denegado',
    verify: 'Verificar Correo',
    welcome: 'Bienvenido',
    profile: 'Perfil',
    logout: 'Salir',
    user_list: 'Listado de Usuarios',
    employer_list: 'Listado de Empleados',
    user: 'Empleados',
    access: 'Permiso de acceso',
    access_list: 'Lista de Permisos',
    client: 'Cliente',
    client_list: 'Listado de Clientes',
    articles: 'Artículos',
    category: 'Categoría',
    category_list: 'Listado de Categorías',
    product_list: 'Listado de Productos',
    modifiers_list: 'Listado de Modifiadores',
    discounts_list: ' Listado de Descuentos',
    shop: 'Tienda',
    resume: 'Resumen',
    sell_product: 'Venta por Productos',
    sell_category: 'Venta por Categorías',
    sell_user: 'Venta por Empleado',
    sell_types_payment: 'Venta por tipos de pago',
    pinlogin: 'Pin Code',
    vending: 'Ventas',
    turnOn: 'Registrar Turno',
    product_add: 'Nuevo Artículo',
    product_edit: 'Editar Artículo'
  },
  // settings
  settings: {
    title: 'Ajuste de Tema',
    color: 'Opciones de color',
    lang: 'Idioma',
    sidebar: 'Opción de barra lateral'
  },
  // rules
  rule: {
    required: '{0} es requerido',
    min: 'La cantidad mínima es de {0} caracteres',
    max: 'La cantidad máxima es de {0} caracteres',
    length: 'Este campo necesita {0} caracteres',
    match: 'Los parámetros {0} y la {1} no son iguales',
    bad_email: '{0} debe ser válido',
    required_element: 'Este campo es requerido',
    bad_phone: '{0} debe ser válido',
    select: 'Seleccione un valor',
    pin: {
      min: 'La cantidad mínima es de {0} digítos',
      max: 'La cantidad máxima es de {0} digítos'
    }
  },
  // profile
  profile: {
    user: 'Usuario',
    edit_profile: 'Editar Perfil',
    sub_profile: 'Complete su perfil',
    btn_edit: 'Actualizar',
    company: 'Compañia',
    manager: 'Propietario'
  },
  messages: {
    refused: 'Fallido: Conexión rechazada.',
    success_avatar: 'La imagen se salvado satisfactoriamente.',
    warning_delete: 'No se podrá revertir esta acción!',
    error_delete_shop: 'Debe existir al menos una tienda',
    error_delete_manager: 'Este usuario no puede ser eliminado',
    error_edit_manager: 'Este usuario no puede ser editado. Use el perfil para editarlo.',
    success_profile: 'Los datos han sido actualizados.',
    success_add: 'El {0} ha sido creado satisfactoriamente.',
    success_up: 'El {0} ha sido actualizado satisfactoriamente.',
    success_del: 'El {0} ha sido eliminado satisfactoriamente.',
    failed_catch: 'Ocurrió error al procesar datos de {0}',
    check_mail:
            'Se ha enviado un correo electrónico con los detalles para cambiar contraseña.',
    password_success: 'La contraseña se actualizo correctamente.',
    warning_create: 'No se puede crear este elemento. Primero debe de crear {0}'
  },
  // options
  actions: {
    actions: 'Acciones',
    accept: 'Aceptar',
    search: 'Buscar',
    new: 'Nuevo',
    newF: 'Nueva',
    edit: 'Editar',
    delete: 'Eliminar',
    list: 'Listar',
    save: 'Guardar',
    cancel: 'Cancelar',
    close: 'Cerrar',
    change: 'Cambiar'
  },
  titles: {
    list: 'Lista de {0}',
    new: 'Nuevo {0}',
    newF: 'Nueva {0}',
    edit: 'Editar {0}',
    show: 'Mostrar {0}',
    delete: 'Eliminar {0}',
    no_action: 'No puede {0}'
  },
  access: {
    key: 'Llave',
    name: 'Nombre',
    accessPin: 'Acceso con Pin',
    accessEmail: 'Acceso con Correo',
    description: 'Descripción'
  },

  component: {
    select: 'Selecciona columnas',
    others: 'otros',
    fieldColor: 'Elige un color'
  },
  tips: {
    account_delete: 'Eliminar cuenta permanentemente.'
  },
  articles: {
    name: 'Artículo',
    names: 'Artículos',
    price: 'Precio',
    cost: 'Coste',
    sell_by: 'Vendido por',
    unit: 'Unidad',
    p_v: 'P/Vol',
    ref: 'REF',
    inventory: 'Inventario',
    composite: 'Artículo compuesto',
    track_inventory: 'Seguir  inventario',
    itbis: 'ITBIS(18%)',
    tax: 'Impuesto',
    lay: 'Propina de ley(10%)',
    composite_text: 'Los artículos compuestos contienen una determinada cantidad de otros artículos'
  },
  panel: {
    basic: 'Básico',
    inventory: 'Inventario',
    variant: 'Variante',
    shop: 'Tienda'
  },
  variants: {
    variant: 'Variante',
    name: 'Nombre',
    price: 'Precio',
    cost: 'Coste',
    ref: 'REF',
    barCode: 'Código de Barras',
    options: 'Opciones'
  },
  shop_article: {
    under_inventory: 'Inventory Under',
    stock: 'En Stock',
    enabled: 'Disponible'
  },
  ...es
}
