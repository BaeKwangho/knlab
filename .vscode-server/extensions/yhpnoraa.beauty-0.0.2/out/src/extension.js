'use strict';
Object.defineProperty(exports, "__esModule", { value: true });
const vscode = require("vscode");
const Pretty = require('prettydiff2');
// activate function
function activate(context) {
    let selections = [];
    let doc = null;
    let editor = null;
    let disposable = vscode.commands.registerCommand('extension.beauty', () => {
        let result = formater(doc.getText(selections[0]));
        editor(selections[0], result);
    });
    //add listener for text selection in the text editor
    vscode.window.onDidChangeTextEditorSelection((e) => {
        selections = e.selections;
        doc = e.textEditor.document;
        editor = (range, str) => {
            e.textEditor.edit(e => {
                e.replace(range, str);
            });
        };
    });
    context.subscriptions.push(disposable);
}
exports.activate = activate;
//format functions
function formater(str) {
    let options = {
        source: str,
        lang: 'auto',
        wrap: 80,
        mode: 'beautify'
    };
    let result = Pretty(options);
    return result;
}
// deactive function
function deactivate() {
    vscode.window.showInformationMessage('Don`t leave me, Master~');
}
exports.deactivate = deactivate;
//# sourceMappingURL=extension.js.map