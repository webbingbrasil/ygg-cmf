import YggActionBarForm from './ActionBarForm';


export function actionBarByContext(context) {
    if (context === 'form') {
        return YggActionBarForm;
    }
}

export {
    YggActionBarForm
};
