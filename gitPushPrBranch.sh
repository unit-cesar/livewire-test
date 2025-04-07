#!/bin/bash

current_branch=$(git rev-parse --abbrev-ref HEAD)
echo -e "Branch atual: $current_branch"

# 1. Solicitar mensagem de commit
read -p "Informe a mensagem do commit: " commit_message

# 2. Se vai criar uma nova TAG
echo
read -p "Deseja criar uma nova TAG (s ou n)? " create_tag

if [ "$create_tag" = "s" ]; then
    read -p "Informe o nome da TAG: " tag_name
fi

# 3. Capturar o nome da branch atual
#current_branch=$(git rev-parse --abbrev-ref HEAD)

# 4. Adicionar e fazer commit
git status
git branch
git add .
git commit -am "$commit_message"

# 5. Se criar uma nova TAG, criar a tag
if [ "$create_tag" = "s" ]; then
    git tag "$tag_name"
fi

# 6. Push
git branch
git push -u origin "$current_branch" --tags

# Perguntar se deseja continuar
echo
read -p "Deseja continuar (s ou n)? " push_continue_process

if [ "$push_continue_process" != "s" ]; then
    echo -e "\nProcesso interrompido."
    exit 0
fi

# 7. Criar PR
echo
echo "Criando PR..."
pr_create_output=$(gh pr create --fill --head "$current_branch" --base main)
echo "$pr_create_output"

# Capturar o ID da PR
pr_id=$(echo "$pr_create_output" | awk -F'/' '{print $NF}')
echo "ID da PR: $pr_id"

# Perguntar se deseja continuar
echo
read -p "Deseja continuar (s ou n)? " continue_process

if [ "$continue_process" != "s" ]; then
    echo -e "\nProcesso interrompido."
    exit 0
fi

# 8. Merge PR
gh pr merge "$pr_id" --rebase --delete-branch

# 9. Comentar no PR
gh pr comment "$pr_id" --body "This PR has been successfully rebased! Branch was deleted. | Thanks for your contribution!"

# 10. Atualizar o repositório local
git status
git pull

# 11. Criar nova branch
echo
read -p "Qual o nome da nova Branch? " new_branch_name
git checkout -b ts$(date +%s)-"$new_branch_name"

echo
read -p "Deseja fazer o BUILD: npm run build (s ou n)? " continue_process
if [ "$continue_process" != "s" ]; then
    echo -e "\nBUILD não realizado."
    # exit 0
else
    npm run build
    npm run clearDev
fi


# echo
# read -p "Deseja enviar para TESTES (s ou n)? " continue_process
# if [ "$continue_process" != "s" ]; then
#     echo -e "\nNão enviado para Testes."
#     #exit 0
# else
#     /usr/bin/rsync -avz --delete --exclude='*storage/*' --exclude='*uploads/*' --exclude='*.git/*' /home/devesa/Documents/GuiaFederal/br_com_guiafederal_erp/ guiafederal@85.31.230.56:/home/guiafederal/web/erp-test.guiafederal.com.br/public_html/
#     npm run clearTest
# fi

# echo
# read -p "Deseja enviar para PRODUÇÃO (s ou n)? " continue_process
# if [ "$continue_process" != "s" ]; then
#     echo -e "\nNão enviado para Produção."
#     #exit 0
# else
#     /usr/bin/rsync -avz --delete --exclude='*storage/*' --exclude='*uploads/*' --exclude='*.git/*' /home/devesa/Documents/GuiaFederal/br_com_guiafederal_erp/ guiafederal@85.31.230.56:/home/guiafederal/web/erp.guiafederal.com.br/public_html/
#     npm run clearProd
# fi

echo
echo
echo "Finalizado!!!"
exit 0

# Criar esse arquivo no diretório raiz do projeto
# chmod +x gitPushPrBranch.sh
# ./gitPushPrBranch.sh
