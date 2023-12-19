//npm install express amqplib
const express = require('express');
const amqp = require('amqplib');

const app = express();
const port = 3000;

app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});

// Connexion à RabbitMQ
const connectToRabbitMQ = async () => {
  try {
    const connection = await amqp.connect('amqp://lsvwlrxa:YbEWJY-AnWenim2yKzHnV4-Dg2tUMrhE@campbell.lmq.cloudamqp.com/lsvwlrxa');
    const channel = await connection.createChannel();
    
    // Déclarer la file de messages
    const queueName = 'messages';
    await channel.assertQueue(queueName, { durable: true });
    
    // Consommation des messages
    channel.consume(queueName, (msg) => {
      console.log(`Message received: ${msg.content.toString()}`);
    }, { noAck: true });

    console.log('Connected to RabbitMQ');
  } catch (error) {
    console.error(`Error connecting to RabbitMQ: ${error}`);
  }
};

// Appeler la fonction de connexion à RabbitMQ
connectToRabbitMQ();
