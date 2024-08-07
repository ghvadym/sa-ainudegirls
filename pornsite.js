const fs = require('fs');
const Anthropic = require('@anthropic-ai/sdk');
const axios = require('axios');
const OpenAI = require('openai');

const anthropic = new Anthropic({
  apiKey: 'YOUR_ANTHROPIC_API_KEY_HERE',
});

const openai = new OpenAI({
  apiKey: 'YOUR_OPENAI_API_KEY_HERE',
});

const UNDETECTABLE_API_KEY = '1721729181930x813884018359923200';

function readJSONFile(filePath) {
  try {
    const data = fs.readFileSync(filePath, 'utf8');
    return JSON.parse(data);
  } catch (error) {
    console.error("Ошибка при чтении JSON файла:", error);
    throw error;
  }
}

function generatePrompt(modelInfo) {
  return `
Task:
Answer questions about ${modelInfo.Name} using the provided information.

Model Information:
ID: ${modelInfo.ID}
Name: ${modelInfo.Name}
Platform: ${modelInfo.Platform}
Photos count: ${modelInfo.Photos_count}
Videos count: ${modelInfo.Videos_count}
Likes count: ${modelInfo.Likes_count}
Pricing: ${modelInfo.Pricing}
Description: ${modelInfo.Description}

CRITICAL INSTRUCTIONS:
1. Replace "provocative" with "sexy" and "sensitive" with "naked".
2. No bullet points.
3. Answer only the question asked. No extra information.
4. Use provided keywords in sequence, without separation.
5. Follow the content guide for each question strictly.
6. Exclude information not in the guide or question.
7. Match example answers' structure, content, and style.
8. Each answer must be unique. Do not repeat exact phrasings from previous answers.
9. Be sure to say if any data is missing. For example: if there is a photo count of 0, but video count of 10, state that there are no photos but there are videos.
10. Aim for responses of approximately 75 words. Responses should be concise but informative, staying close to this word count.

Questions and answers:
Example answers (USE AS GENERAL GUIDES, NOT EXACT TEMPLATES):
Describe me ${modelInfo.Name}:
${modelInfo.Name} is a sexy AI-generated model from France with a Capricorn zodiac sign. She's active on Fanvue, offering a tantalizing collection of ${modelInfo.Photos_count} photos and ${modelInfo.Videos_count} videos for her subscribers. With a monthly fee of ${modelInfo.Pricing}, fans can access her exclusive naked content. As a virtual girl and HelloFace ambassador, ${modelInfo.Name} provides a unique experience for her followers. Her Instagram account offers additional content, and she expresses gratitude for her supporters' dedication. ${modelInfo.Name} AI-generated persona adds an intriguing element to her online presence.

Who is ${modelInfo.Name}? (Keywords: ${modelInfo.Name} nude model)
${modelInfo.Name} nude model is a popular online person with a huge and provocative portfolio of works. She has been widely docketed on a site offering a content subscription service named Fanvue, where she provides an option for subscribers to avail exclusive content. ${modelInfo.Name} is known for her sultry demeanor and interactive nature; at times, she makes specialized content that suits her fans. Her content varies from provocative photos and videos to more personal, exclusive photos. ${modelInfo.Name} is a prominent phenomenon in the world of adult entertainment; she serves as a professional trying to succeed.

How many subscribers does ${modelInfo.Name} Fanvue have? (Keywords: ${modelInfo.Name} nude model)
The exact number of subscribers that ${modelInfo.Name} has on Fanvue is not publicly disclosed on the platform. However, ${modelInfo.Name} nude model has gained a super impressive number of votes —her Fanvue has ${modelInfo.Likes_count} likes. Such a huge number of likes does nothing else but underline how popular she is and how engaging she is with her fans in general. With such a huge fan base, ${modelInfo.Name} has surely now become one of the leading profiles on the platform to offer top-quality exclusive content to keep her audience engaged. And the success is apparent regarding that, proving unmatched energy for unique personal experiences. Fans can support her work with a subscription and get more from her photo and video gallery and engaging content.

Is ${modelInfo.Name} Fanvue worth it? (Keywords: ${modelInfo.Name} Fanvue)
${modelInfo.Name} Fanvue is worth subscribing to based on personal preferences and expectations. Her subscription is sure to be worth it for fans who want good quality, exclusive adult content and engagingly interact with the creator herself. ${modelInfo.Name} is likely to post frequently and show up frequently with her fans, providing a multi-content portfolio suitable for various fan appetites. The more personalized interaction promised may actually be a novel, even very satisfying, experience for subscription holders. Not any case of public nudity or other porn video recording incidents would be detected if not fully searched by the subscriber.

Where can I find ${modelInfo.Name} nude photos and videos? (Keywords: ${modelInfo.Name} nude pictures, ${modelInfo.Name} tits)
${modelInfo.Name} does her nude photos and videos through OnlyFans and makes them available to followers on her FanVue profile. Fanvue is a subscription-based service where models share exclusive adult-oriented content with their paying followers. It's subscribed followers upon ${modelInfo.Name} profile that get access to an overwhelming number of ${modelInfo.Name} nude pictures as well as ${modelInfo.Name} tits photos. The content isn't free; it isn't found beyond the walls of the platform, so subscribers have a unique and private experience. The blog on her webpage details that many sexy, nude pictures of her are available to view, with videos included, on her Fanvue profile.

Does ${modelInfo.Name} have sexy naked pics and nude videos on Fanvue? (Keywords: ${modelInfo.Name} sex, ${modelInfo.Name} porn)
Yes, ${modelInfo.Name} has sexy naked pictures and nude videos on her Fanvue webpage. The content created by this model is designed to be fun and create an intimate experience, from material that comes in the form of explicit and suggestive stuff. Fans are definitely treated to a variety of ${modelInfo.Name} sex photos and ${modelInfo.Name} porn videos that showcase her appeal and artistic side, making her profile on Fanvue a favorite for fans looking for exclusivity and quality in their sex work. Subscribers also receive full access to the amazing sexy and nude content, which lets viewers enjoy it fully.

How many nude photos and likes does ${modelInfo.Name} have? (Keywords: ${modelInfo.Name} naked pics, ${modelInfo.Name} boobs)
${modelInfo.Name} currently holds ${modelInfo.Photos_count} nude photos under her Fanvue profile. The large number she holds under these numbers speaks volumes that ${modelInfo.Name} is one of the most consistent models, ensuring subscribers get content in the quantity that befits her status. ${modelInfo.Name} naked pics have accrued high engagement by noticing a good number of likes and comments from her most loyal fans. This volume of {modelInfo.Name} boobs will ensure that subscribers never run out of things new and exciting in her feed. Subscribe to Fanvue to catch everything exciting and to become a part of the community surrounding the hottest content online.

How many naked videos does ${modelInfo.Name} have? (Keywords: ${modelInfo.Name} nudes)
As of now, there are a total of ${modelInfo.Videos_count} ${modelInfo.Name} nudes' videos that are all uploaded to her profile on Fanvue. This number clearly reflects the time and effort she puts into providing different content to her fans. Each video demonstrates her creativity and dedication to pulling her fans with fresh, exclusive content. The audience will find all kinds of intimate and XXX videos for a fresh and engaging experience. By subscribing to her Fanvue profile, fans are going to access all ${modelInfo.Videos_count} videos and, later in the future, stay updated on any new content she keeps adding to her library.

How much do ${modelInfo.Name} nudes cost? (Keywords: ${modelInfo.Name} nudes)
${modelInfo.Name} nudes can be accessed at such an attractive price of ${modelInfo.Pricing} per month on Fanvue. Well, her fans are able to watch every photo and video set in full. Another pro of having the price lower is that the account becomes available to a much wider audience, delivering the fullest value to such high-quality and unique content. Let there be constant updating for the most intimate needs of any taste.

Is ${modelInfo.Name} a real person? (Keywords: ${modelInfo.Name} naked)
${modelInfo.Name} is an AI model  who is actively working within the Fanvue system, and there she offers personal and exclusive material to her fans. ${modelInfo.Name} engagement with fans on Fanvue confirms the authenticity of this presence. ${modelInfo.Name} naked model has a lot of content to present her as an individual and creative mind, which makes her a darling of many fans in adult entertainment. Her fans can interact with her live on the Fanvue platform while experiencing new, exclusive content from her account.

Where can I find ${modelInfo.Name} on social media? (Keywords: ${modelInfo.Name} nude)
For more details on ${modelInfo.Name} nude model's social media presence, fans can first visit her Fanvue page, where she shares links and additional information about her official accounts. ${modelInfo.Name} AI is active on Fanvu platform, reaching out to fans and promoting her content. Perhaps, she has other social media accounts like Twitter or Instagram, but we do not know it exactly.

How can I get in contact with ${modelInfo.Name}? (Keywords: ${modelInfo.Name} nude model)
Fans should subscribe to ${modelInfo.Name} nude model profile on Fanvue, as the platform provides the feature of messaging from the website to get in touch with ${modelInfo.Name}. Fanvue is a subscription-based media platform that allows AI models to interact with their fans in a very personal and somewhat private setting through recorded messages. Also, ${modelInfo.Name} may have released contact information or business contact information on her Fanvue page or related social media. This will engage the subscribers deeper into her content so they can find ways to engage with her directly for a much more intimate and interactive experience.

How does ${modelInfo.Name} make money? (Keywords: ${modelInfo.Name} naked)
${modelInfo.Name} naked model raises the most money from FanVue where she needs the fans to pay some cash to access the premium content online. She does it by selling subscriptions. Through subscriptions, the fans can enjoy photos and videos, and other personal interactions. In addition to the subscription fee, she monetizes via potentially promotional deals or sponsorships.

REMINDER:
Capture examples' essence and flow while creating unique responses for ${modelInfo.Name}. Be creative in phrasing while maintaining overall structure and key points.
`;
}

async function processTextWithUndetectable(text) {
  const submitUrl = "https://api.undetectable.ai/submit";
  const submitPayload = {
    content: text,
    readability: "Journalist",
    purpose: "General Writing",
    strength: "More Human"
  };

  try {
    const submitResponse = await axios.post(submitUrl, submitPayload, {
      headers: {
        'api-key': UNDETECTABLE_API_KEY,
        'Content-Type': 'application/json'
      }
    });

    const documentId = submitResponse.data.id;

    const checkUrl = "https://api.undetectable.ai/document";
    let processedText = null;

    while (!processedText) {
      await new Promise(resolve => setTimeout(resolve, 15000));

      const checkResponse = await axios.post(checkUrl, { id: documentId }, {
        headers: {
          'api-key': UNDETECTABLE_API_KEY,
          'Content-Type': 'application/json'
        }
      });

      if (checkResponse.data.status === 'done') {
        processedText = checkResponse.data.output;
      }
    }

    return processedText;
  } catch (error) {
    console.error("Ошибка при обработке текста в undetectable.ai:", error);
    throw error;
  }
}

async function processTextWithOpenAI(text, question, keywords, modelName) {
  const prompt = `Process the given answer text as follows: 1. Insert the exact keywords only once if they are not already present in the text. 2. Place the keywords as a single, unbroken phrase where most relevant in the text. 3. Do not separate the keywords with commas, spaces, or any other punctuation or words. 4. Do not change or remove any existing content in the text. 5. Replace <Name of model> with the actual model's name. 6. Insert the keywords only once, even if there are multiple relevant places. 7. Return only the modified answer text, without the question or any additional comments.

Question: ${question}
Keywords: ${keywords}

Answer text:
${text}

Process the text and return only the modified version with keywords inserted as specified.`;

  try {
    const response = await openai.chat.completions.create({
      model: "gpt-4o-mini",
      messages: [{ role: "user", content: prompt }],
      max_tokens: 1024,
    });

    return response.choices[0].message.content.trim();
  } catch (error) {
    console.error("Ошибка при обработке текста в OpenAI:", error);
    throw error;
  }
}

async function askQuestion(modelInfo, question, keywords, wordCount, guide) {
  const prompt = generatePrompt(modelInfo);
  const fullQuestion = `
${prompt}

Question: ${question} (Keywords: ${keywords})
Word count: ${wordCount}
Guide: ${guide}

Please provide an answer based on the information given and the instructions provided earlier.
  `;

  try {
    const response = await anthropic.messages.create({
      model: "claude-3-5-sonnet-20240620",
      max_tokens: 1024,
      messages: [
        { role: "user", content: fullQuestion }
      ]
    });

    const generatedText = response.content[0].text;
    const processedTextUndetectable = await processTextWithUndetectable(generatedText);
    const finalProcessedText = await processTextWithOpenAI(processedTextUndetectable, question, keywords, modelInfo.Name);

    const result = {
      text: finalProcessedText,
      usage: {
        inputTokens: response.usage.input_tokens,
        outputTokens: response.usage.output_tokens
      }
    };

    return result;

  } catch (error) {
    console.error("Произошла ошибка при генерации или обработке текста:", error);
    throw error;
  }
}

async function main() {
  try {
    const modelsData = readJSONFile('models.json');
    const questions = [
      {
        question: "Who is {Name}?",
        keywords: "{Name} nude model",
        wordCount: 75,
        guide: "Include information about {Name}'s identity, her professional activities, and why she's popular on {Platform}."
      },
      // остальные вопросы
    ];

    for (const modelInfo of modelsData) {
      console.log(`Генерация ответов для ${modelInfo.Name}...`);
      for (const q of questions) {
        const question = q.question.replace(/{Name}/g, modelInfo.Name).replace(/{Platform}/g, modelInfo.Platform);
        const keywords = q.keywords.replace(/{Name}/g, modelInfo.Name);
        const guide = q.guide.replace(/{Name}/g, modelInfo.Name).replace(/{Platform}/g, modelInfo.Platform);

        const result = await askQuestion(modelInfo, question, keywords, q.wordCount, guide);
        console.log(`Вопрос: ${question}`);
        console.log("Обработанный ответ:", result.text);
        console.log("-------------------------------");
      }
    }
  } catch (error) {
    console.error("Ошибка в main:", error);
  }
}

main();