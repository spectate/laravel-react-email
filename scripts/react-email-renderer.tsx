import {render} from '@react-email/render';
import React from 'react';
import path from 'path';

const [, , template] = process.argv;

if (!template) {
    console.error('Usage: tsx react-email-renderer.tsx <template>');
    process.exit(1);
}

async function renderEmailTemplate(template: string) {
    try {
        const importedModule = await import(path.resolve(template));
        const EmailComponent = importedModule.default || importedModule;

        if (typeof EmailComponent !== 'function') {
            throw new Error('Default export is not a React component');
        }

        // Render the component to HTML
        const html = await render(<EmailComponent/>, {
            pretty: true,
        });

        const variables: string[] = [];
        html.replace(/\$\$(\w+)\$\$/g, (match, variable) => {
            if (!variables.includes(variable)) {
                variables.push(variable);
            }
            return match;
        });

        let plainText = await render(<EmailComponent/>, {
            plainText: true,
        });

        variables.forEach(variable => {
            const uppercaseVar = variable.toUpperCase();
            const regex = new RegExp(`\\$${uppercaseVar}\\$`, 'g');
            plainText = plainText.replace(regex, `$$${variable}$$`);
        });

        // Replace $$vars$$ with Laravel Blade variables
        const bladeHtml = html.replace(/\$\$(\w+)\$\$/g, '{{ \$$$1 }}');
        const bladePlainText = plainText.replace(/\$\$(\w+)\$\$/g, '{{ \$$$1 }}');

        // Output the rendered Blade-compatible HTML and text to stdout
        console.log(JSON.stringify({html: bladeHtml, plainText: bladePlainText}));
    } catch (error) {
        if (error instanceof Error) {
            console.error(error.message);
        }
        process.exit(1);
    }
}

renderEmailTemplate(template);
